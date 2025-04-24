<?php
session_start();
include 'db.php';

if (!isset($_SESSION['seller_name'])) {
    header("Location: login.php");
    exit;
}

$seller_name = $_SESSION['seller_name'];

// Total Sales
$stmt = $conn->prepare("
    SELECT SUM(oi.price * oi.quantity) AS total_sales
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    WHERE p.seller_name = ?
");
$stmt->execute([$seller_name]);
$total_sales = $stmt->fetchColumn() ?? 0;

// Total Orders
$stmt = $conn->prepare("
    SELECT COUNT(DISTINCT oi.order_id) AS total_orders
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    WHERE p.seller_name = ?
");
$stmt->execute([$seller_name]);
$total_orders = $stmt->fetchColumn() ?? 0;

// Top‑Selling Products
$stmt = $conn->prepare("
    SELECT oi.product_name, SUM(oi.quantity) AS total_sold
    FROM order_items oi
    JOIN products p ON oi.product_id = p.id
    WHERE p.seller_name = ?
    GROUP BY oi.product_id
    ORDER BY total_sold DESC
    LIMIT 5
");
$stmt->execute([$seller_name]);
$top_selling = $stmt->fetchAll();

// Recent Orders
$stmt = $conn->prepare("
    SELECT o.id AS order_id, o.name, o.phone, o.address, o.payment_method, o.total_price, o.order_date,
           oi.product_name, oi.quantity, oi.price, oi.image
    FROM order_items oi
    JOIN orders o     ON oi.order_id   = o.id
    JOIN products p   ON oi.product_id = p.id
    WHERE p.seller_name = ?
    ORDER BY o.order_date DESC
    LIMIT 5
");
$stmt->execute([$seller_name]);
$recent_orders = $stmt->fetchAll();
// Average Order Value (AOV)
$aov = $total_orders ? $total_sales / $total_orders : 0;
// Revenue Over Time
$stmt = $conn->prepare("
    SELECT DATE(o.order_date) AS day, SUM(oi.price * oi.quantity) AS revenue
    FROM order_items oi
    JOIN orders o ON oi.order_id = o.id
    JOIN products p ON oi.product_id = p.id
    WHERE p.seller_name = ?
    GROUP BY day
    ORDER BY day
");
$stmt->execute([$seller_name]);
$revenue_over_time = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Seller Dashboard</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
  <style>
    * { box-sizing: border-box; margin: 0; padding: 0; font-family: Arial, sans-serif; }
    body { background: #f4f4f4; color: #333; }
    .container { max-width: 800px; margin: 0 auto; padding: 16px; }
    .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
    .header h1 { font-size: 24px; }
    .status-icon {
      width: 40px; height: 40px;
      background: #000; color: #fff;
      display: flex; align-items: center; justify-content: center;
      border-radius: 50%; font-weight: bold;
    }
    .stats-grid {
      display: grid;
      grid-template-columns: 1fr;
      gap: 16px;
      margin-bottom: 24px;
    }

    .card {
      background: #fff; padding: 24px;
      border-radius: 16px; text-align: center;
      display: flex; flex-direction: column;
      justify-content: center; align-items: center;
      height: 160px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    .card.black { background: #000; color: #fff; }
    .card.white { background: #fff; color: #333; }
    .card h2 { font-size: 32px; margin-bottom: 8px; }
    .card p { font-size: 14px; opacity: 0.7; }

    .chart-card, .trending-card, .orders-card {
      background: #fff; padding: 16px; border-radius: 12px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 24px;
    }
    .chart-card h3, .trending-card h3, .orders-card h3 { margin-bottom: 12px; font-size: 18px; }

    .trending-list { list-style: none; }
    .trending-item {
      display: flex; justify-content: space-between;
      padding: 8px 0; border-bottom: 1px solid #ddd;
    }
    .trending-item:last-child { border-bottom: none; }

    .orders-list { display: flex; flex-direction: column; gap: 16px; }
    .order-item { display: flex; gap: 16px; }
    .order-item img {
      width: 64px; height: 64px; object-fit: cover;
      border-radius: 8px;
    }
    .order-info h4 { font-size: 16px; margin-bottom: 4px; }
    .order-info .qty { font-size: 14px; color: #666; margin-bottom: 4px; }
    .order-info .meta { font-size: 12px; color: #999; }

canvas{
    display:flex;
    justify-content:left;
    text-align:left;
}
 /* Stats Grid */
 .stats-grid { display: grid; grid-template-columns: 1fr; gap: 16px; margin-bottom: 24px; }
    @media(min-width: 768px) { .stats-grid { grid-template-columns: 1fr 1fr 1fr; } }

    .card { background: #fff; padding: 24px; border-radius: 16px; text-align: center; display: flex; flex-direction: column; justify-content: center; align-items: center; height: 160px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
    .card h2 { font-size: 32px; margin-bottom: 8px; }
    .card p { font-size: 14px; opacity: 0.7; }
    .card.black { background: #000; color: #fff; }
    .card.white { background: #fff; color: #333; }

  </style>
</head>
<body>
  <div class="container">
    <!-- Header -->
    <div class="header">
      <h1>Analytics</h1>
      <div class="status-icon">🛍️</div>
    </div>

    <!-- Stats -->
    <div class="stats-grid">
      <div class="card black">
        <h2><?= $total_orders ?></h2>
        <p>Total Orders</p>
      </div>
      <div class="card white">
        <h2>₦<?= number_format($total_sales, 2) ?></h2>
        <p>Total Sales</p>
      </div>
      <div class="card white">
        <h2>₦<?= number_format($aov, 2) ?></h2>
        <p>Avg. Order Value</p>
      </div>
    </div>
    <!-- Top Selling Products Chart -->
    <div class="chart-card">
      <h3>Top Selling Products</h3>
      <canvas
  id="topSellingChart"
  width="500"
  height="500"
  style="
    display: block;
    margin-right: ;
    background-color: #ffffff;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    padding: 10px;
  "
></canvas>


    </div>

    <!-- Trending Items -->
    <div class="trending-card">
      <h3>Trending Items</h3>
      <ul class="trending-list">
        <?php foreach ($top_selling as $item): ?>
          <li class="trending-item">
            <span><?= htmlspecialchars($item['product_name']) ?></span>
            <strong><?= $item['total_sold'] ?> sold</strong>
          </li>
        <?php endforeach; ?>
      </ul>
    </div>
 <!-- Revenue Over Time -->
 <div class="chart-container">
      <h3>Revenue Over Time</h3>
      <canvas id="revenueChart"  width="500"
  height="500"
  style="
    display: block;
    margin-right: ;
    background-color: #ffffff;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    padding: 10px;
  "></canvas>
    </div>
  </div>
    <!-- Recent Orders -->
    <div class="orders-card">
      <h3>Recent Orders</h3>
      <div class="orders-list">
        <?php foreach ($recent_orders as $order): ?>
          <div class="order-item">
            <img src="<?= htmlspecialchars($order['image']) ?>" alt="">
            <div class="order-info">
              <h4><?= htmlspecialchars($order['product_name']) ?></h4>
              <div class="qty">₦<?= number_format($order['price'], 2) ?> | Qty: <?= $order['quantity'] ?></div>
              <div class="meta">
                <?= htmlspecialchars($order['order_date']) ?> • <?= htmlspecialchars($order['payment_method']) ?>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

<script>
  const names = <?= json_encode(array_column($top_selling, 'product_name')) ?>;
  const sold  = <?= json_encode(array_column($top_selling, 'total_sold')) ?>;

  const ctx = document.getElementById('topSellingChart').getContext('2d');
  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: names,
      datasets: [{
        label: 'Units Sold',
        data: sold,
        backgroundColor: 'rgba(0, 0, 0, 0.9)',
        borderColor: '#fff',
        borderWidth: 1,
        borderRadius: 6
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: {
          display: false
        },
        datalabels: {
          color: '#fff',
          anchor: 'end',
          align: 'start',
          font: {
            weight: 'bold'
          },
          formatter: value => value
        }
      },
      scales: {
        y: {
          beginAtZero: true,
          ticks: {
            stepSize: 1,
            color: '#000'
          },
          grid: {
            color: '#000'
          }
        },
        x: {
  ticks: {
    display: false // Hides the labels completely
  },
  grid: {
    color: '#000'
  }
}

      }
    },
    plugins: [ChartDataLabels]
  });
  // Revenue Over Time
  const revLabels = <?= json_encode(array_column($revenue_over_time,'day')) ?>;
    const revData   = <?= json_encode(array_column($revenue_over_time,'revenue')) ?>;
    new Chart(document.getElementById('revenueChart'), {
      type:'line',
      data:{
        labels: revLabels,
        datasets:[{
          label: 'Revenue',
          data: revData,
          borderColor: '#000',
          backgroundColor: 'rgba(5, 5, 5, 0.2)',
          tension: 0.1,
          fill: true
        }]
      },
      options:{
        responsive:true,
        plugins:{ legend:{ display:false } },
        scales:{
          x:{ ticks:{ color:'#000' }, grid:{ display:false } },
          y:{ beginAtZero:true, ticks:{ color:'#000' }, grid:{ color:'#ddd' } }
        }
      }
    });
</script>

</body>
</html>
