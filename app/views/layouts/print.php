<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <title>Print - Stuarz</title>
  <style>
    /* Minimal print-friendly styles */
    html,body{margin:0;padding:20px;font-family:Arial,Helvetica,sans-serif;color:#000;background:#fff}
    header,footer,nav,aside,.sidebar,.chat-modal,.ai-helper{display:none !important}
    .print-article{max-width:780px;margin:0 auto}
    .print-title{font-size:28px;margin-bottom:8px}
    .print-meta{color:#444;font-size:13px;margin-bottom:18px}
    .print-content{font-size:15px;line-height:1.7;white-space:pre-wrap}
    @media print{
      body{padding:0}
    }
  </style>
</head>
<body>
  <div class="print-article">
    <?php include $content; ?>
  </div>
  <script>
    // Auto open print dialog when loading the print view
    if (window.matchMedia) {
      // small delay to ensure rendering
      setTimeout(function(){ window.print(); }, 250);
    }
  </script>
</body>
</html>