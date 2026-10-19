<?php
require_once '../../config/koneksi.php';
require_once '../../includes/auth.php';
require_role('admin');

$title = 'Rekap Pembayaran Bulanan';
$currentYear = (int) date('Y');
$requestedYear = filter_input(INPUT_GET, 'tahun', FILTER_VALIDATE_INT);
$selectedYear = ($requestedYear !== false && $requestedYear !== null && $requestedYear >= 2000 && $requestedYear <= 2100)
    ? $requestedYear
    : $currentYear;

$yearQuery = $pdo->query(
    "SELECT DISTINCT YEAR(tanggal_bayar) AS tahun
     FROM pembayaran
     WHERE tanggal_bayar IS NOT NULL
     ORDER BY tahun DESC"
);
$availableYears = array_map('intval', array_column($yearQuery->fetchAll(), 'tahun'));
$availableYears[] = $currentYear;
$availableYears[] = $selectedYear;
$availableYears = array_values(array_unique($availableYears));
rsort($availableYears);

$startDate = sprintf('%04d-01-01', $selectedYear);
$endDate = sprintf('%04d-01-01', $selectedYear + 1);

$stmt = $pdo->prepare(
    "SELECT
        MONTH(tanggal_bayar) AS bulan,
        COUNT(*) AS total_transaksi,
        SUM(CASE WHEN status = 'lunas' THEN 1 ELSE 0 END) AS jumlah_lunas,
        SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) AS jumlah_pending,
        SUM(CASE WHEN status = 'gagal' THEN 1 ELSE 0 END) AS jumlah_gagal,
        SUM(CASE WHEN status = 'lunas' THEN jumlah ELSE 0 END) AS nominal_lunas,
        SUM(CASE WHEN status = 'pending' THEN jumlah ELSE 0 END) AS nominal_pending,
        SUM(CASE WHEN status = 'gagal' THEN jumlah ELSE 0 END) AS nominal_gagal
     FROM pembayaran
     WHERE tanggal_bayar >= ? AND tanggal_bayar < ?
     GROUP BY MONTH(tanggal_bayar)
     ORDER BY MONTH(tanggal_bayar)"
);
$stmt->execute([$startDate, $endDate]);
$rows = $stmt->fetchAll();

$months = [
    1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
    5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
    9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
];

$monthly = [];
foreach ($months as $number => $monthName) {
    $monthly[$number] = [
        'bulan' => $monthName,
        'total_transaksi' => 0,
        'jumlah_lunas' => 0,
        'jumlah_pending' => 0,
        'jumlah_gagal' => 0,
        'nominal_lunas' => 0,
        'nominal_pending' => 0,
        'nominal_gagal' => 0,
    ];
}

foreach ($rows as $row) {
    $monthNumber = (int) $row['bulan'];
    if (isset($monthly[$monthNumber])) {
        foreach ([
            'total_transaksi', 'jumlah_lunas', 'jumlah_pending', 'jumlah_gagal',
            'nominal_lunas', 'nominal_pending', 'nominal_gagal'
        ] as $field) {
            $monthly[$monthNumber][$field] = (float) ($row[$field] ?? 0);
        }
        $monthly[$monthNumber]['jumlah_lunas'] = (int) $monthly[$monthNumber]['jumlah_lunas'];
        $monthly[$monthNumber]['jumlah_pending'] = (int) $monthly[$monthNumber]['jumlah_pending'];
        $monthly[$monthNumber]['jumlah_gagal'] = (int) $monthly[$monthNumber]['jumlah_gagal'];
        $monthly[$monthNumber]['total_transaksi'] = (int) $monthly[$monthNumber]['total_transaksi'];
    }
}

$totals = [
    'total_transaksi' => 0,
    'jumlah_lunas' => 0,
    'jumlah_pending' => 0,
    'jumlah_gagal' => 0,
    'nominal_lunas' => 0,
    'nominal_pending' => 0,
    'nominal_gagal' => 0,
];
foreach ($monthly as $monthData) {
    foreach ($totals as $field => $unused) {
        $totals[$field] += $monthData[$field];
    }
}

$nullDateStmt = $pdo->query(
    "SELECT COUNT(*) AS jumlah,
            COALESCE(SUM(CASE WHEN status = 'lunas' THEN jumlah ELSE 0 END), 0) AS nominal_lunas
     FROM pembayaran
     WHERE tanggal_bayar IS NULL"
);
$withoutDate = $nullDateStmt->fetch();

$maxMonthlyIncome = 0;
foreach ($monthly as $monthData) {
    $maxMonthlyIncome = max($maxMonthlyIncome, (float) $monthData['nominal_lunas']);
}

function rupiah($amount): string
{
    return 'Rp ' . number_format((float) $amount, 0, ',', '.');
}

include '../../includes/header.php';
?>
<div class="page-head recap-page-head">
    <div>
        <h1>Rekap Pembayaran Bulanan</h1>
        <p class="muted">Ringkasan pembayaran PetCare berdasarkan tanggal bayar pada tahun <?= e((string) $selectedYear) ?>.</p>
    </div>
    <div class="recap-actions no-print">
        <a class="btn btn-secondary" href="index.php">Kembali ke Pembayaran</a>
        <button class="btn btn-primary" type="button" onclick="window.print()">Cetak Rekap</button>
    </div>
</div>

<div class="card recap-filter no-print">
    <form method="get" class="year-filter">
        <label for="tahun">Pilih tahun rekap</label>
        <select name="tahun" id="tahun">
            <?php foreach ($availableYears as $year): ?>
                <option value="<?= $year ?>" <?= $selectedYear === $year ? 'selected' : '' ?>><?= $year ?></option>
            <?php endforeach; ?>
        </select>
        <button class="btn btn-primary" type="submit">Tampilkan</button>
    </form>
</div>

<div class="stat-grid recap-stat-grid">
    <div class="stat-card recap-income">
        <span>Total pemasukan (lunas)</span>
        <strong><?= e(rupiah($totals['nominal_lunas'])) ?></strong>
        <small><?= number_format($totals['jumlah_lunas'], 0, ',', '.') ?> transaksi lunas</small>
    </div>
    <div class="stat-card">
        <span>Pembayaran pending</span>
        <strong><?= number_format($totals['jumlah_pending'], 0, ',', '.') ?></strong>
        <small>Nilai: <?= e(rupiah($totals['nominal_pending'])) ?></small>
    </div>
    <div class="stat-card">
        <span>Pembayaran gagal</span>
        <strong><?= number_format($totals['jumlah_gagal'], 0, ',', '.') ?></strong>
        <small>Nilai tercatat: <?= e(rupiah($totals['nominal_gagal'])) ?></small>
    </div>
    <div class="stat-card">
        <span>Total transaksi bertanggal</span>
        <strong><?= number_format($totals['total_transaksi'], 0, ',', '.') ?></strong>
        <small>Januari–Desember <?= e((string) $selectedYear) ?></small>
    </div>
</div>

<div class="card recap-chart-card">
    <div class="recap-section-title">
        <div>
            <h2>Pemasukan per Bulan</h2>
            <p class="muted">Hanya menghitung pembayaran dengan status lunas.</p>
        </div>
    </div>
    <div class="monthly-bars">
        <?php foreach ($monthly as $monthData): ?>
            <?php
                $income = (float) $monthData['nominal_lunas'];
                $barHeight = $maxMonthlyIncome > 0 ? max(3, (int) round(($income / $maxMonthlyIncome) * 100)) : 3;
            ?>
            <div class="monthly-bar-item" title="<?= e($monthData['bulan']) ?>: <?= e(rupiah($income)) ?>">
                <strong><?= e(rupiah($income)) ?></strong>
                <div class="monthly-bar-track">
                    <div class="monthly-bar-fill" style="height: <?= $barHeight ?>%"></div>
                </div>
                <span><?= e(substr($monthData['bulan'], 0, 3)) ?></span>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<div class="card table-wrap recap-table-card">
    <div class="recap-section-title">
        <div>
            <h2>Rincian per Bulan</h2>
            <p class="muted">Transaksi dikelompokkan berdasarkan kolom tanggal_bayar.</p>
        </div>
    </div>
    <table class="recap-table">
        <thead>
            <tr>
                <th>Bulan</th>
                <th>Total Transaksi</th>
                <th>Lunas</th>
                <th>Pending</th>
                <th>Gagal</th>
                <th>Pemasukan Lunas</th>
                <th>Nominal Pending</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($monthly as $monthData): ?>
                <tr>
                    <td><?= e($monthData['bulan']) ?></td>
                    <td><?= number_format($monthData['total_transaksi'], 0, ',', '.') ?></td>
                    <td><span class="status-pill status-lunas"><?= number_format($monthData['jumlah_lunas'], 0, ',', '.') ?></span></td>
                    <td><span class="status-pill status-pending"><?= number_format($monthData['jumlah_pending'], 0, ',', '.') ?></span></td>
                    <td><span class="status-pill status-gagal"><?= number_format($monthData['jumlah_gagal'], 0, ',', '.') ?></span></td>
                    <td class="money-cell"><?= e(rupiah($monthData['nominal_lunas'])) ?></td>
                    <td class="money-cell"><?= e(rupiah($monthData['nominal_pending'])) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th>Total <?= e((string) $selectedYear) ?></th>
                <th><?= number_format($totals['total_transaksi'], 0, ',', '.') ?></th>
                <th><?= number_format($totals['jumlah_lunas'], 0, ',', '.') ?></th>
                <th><?= number_format($totals['jumlah_pending'], 0, ',', '.') ?></th>
                <th><?= number_format($totals['jumlah_gagal'], 0, ',', '.') ?></th>
                <th><?= e(rupiah($totals['nominal_lunas'])) ?></th>
                <th><?= e(rupiah($totals['nominal_pending'])) ?></th>
            </tr>
        </tfoot>
    </table>
</div>

<?php if ((int) $withoutDate['jumlah'] > 0): ?>
    <div class="alert alert-warning recap-date-warning">
        Ada <?= number_format((int) $withoutDate['jumlah'], 0, ',', '.') ?> pembayaran yang belum mempunyai tanggal bayar. Data tersebut belum dimasukkan ke rekap bulan mana pun.
        <?php if ((float) $withoutDate['nominal_lunas'] > 0): ?>Nilai pembayaran lunas tanpa tanggal: <strong><?= e(rupiah($withoutDate['nominal_lunas'])) ?></strong>.<?php endif; ?>
        Lengkapi tanggalnya melalui menu Pembayaran agar masuk ke rekap yang sesuai.
    </div>
<?php endif; ?>

<p class="muted recap-footnote">Laporan dibuat pada <?= e(date('d-m-Y H:i')) ?>. Pemasukan dihitung dari pembayaran berstatus <strong>lunas</strong> yang memiliki tanggal bayar.</p>

<?php include '../../includes/footer.php'; ?>
