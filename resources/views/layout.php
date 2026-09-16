<?php
$escape = static fn (mixed $value): string => htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
?>
<!doctype html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?= $escape($description ?? '') ?>">
    <meta name="csrf-token" content="<?= $escape($csrfToken ?? '') ?>">
    <title><?= $escape($title ?? 'Mersin Modern') ?></title>
    <link rel="icon" type="image/svg+xml" href="/assets/images/favicon.svg">
    <link rel="stylesheet" href="/assets/css/app.css">
    <script type="module" src="/assets/js/app.js"></script>
</head>
<body class="bg-paper text-ink antialiased">
<?= $content ?>
</body>
</html>
