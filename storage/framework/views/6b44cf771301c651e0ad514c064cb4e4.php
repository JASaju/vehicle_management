<?php # [BlazeVersion]:{v3} ?>
<!doctype html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Manage vehicles, drivers, maintenance, fuel, parking, and reports in one vehicle-management workspace.">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?php echo e($canonicalUrl); ?>">
    <meta property="og:type" content="website">
    <meta property="og:site_name" content="Vehicle Manager">
    <meta property="og:title" content="Vehicle Manager | Fleet and Vehicle Management">
    <meta property="og:description" content="Manage vehicles, drivers, maintenance, fuel, parking, and reports in one vehicle-management workspace.">
    <meta property="og:url" content="<?php echo e($canonicalUrl); ?>">
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="Vehicle Manager | Fleet and Vehicle Management">
    <meta name="twitter:description" content="Manage vehicles, drivers, maintenance, fuel, parking, and reports in one vehicle-management workspace.">
    <title>Vehicle Manager | Fleet and Vehicle Management</title>
    <style>
        :root { color-scheme: light; font-family: system-ui, -apple-system, "Segoe UI", sans-serif; color: #172033; background: #f7f9fc; }
        * { box-sizing: border-box; }
        body { margin: 0; }
        a { color: inherit; }
        .shell { max-width: 1080px; margin: 0 auto; padding: 0 24px; }
        header { display: flex; align-items: center; justify-content: space-between; min-height: 76px; }
        .brand { color: #4338ca; font-weight: 750; font-size: 1.15rem; text-decoration: none; }
        .button { display: inline-block; border-radius: 8px; padding: 11px 18px; color: white; background: #4338ca; font-weight: 650; text-decoration: none; }
        main { padding: 64px 0 76px; }
        .hero { max-width: 720px; margin: 0 auto 52px; text-align: center; }
        h1 { margin: 0 0 18px; font-size: clamp(2.2rem, 6vw, 3.8rem); line-height: 1.08; letter-spacing: -0.04em; }
        .intro { margin: 0 auto 28px; max-width: 620px; color: #536078; font-size: 1.15rem; line-height: 1.7; }
        h2 { margin: 0 0 18px; font-size: 1.45rem; }
        .features { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 16px; }
        .feature { padding: 22px; border: 1px solid #e1e6ef; border-radius: 12px; background: white; }
        .feature h3 { margin: 0 0 8px; font-size: 1.05rem; }
        .feature p { margin: 0; color: #536078; line-height: 1.6; }
        footer { padding: 24px 0; border-top: 1px solid #e1e6ef; color: #667085; font-size: .9rem; }
        @media (max-width: 560px) { main { padding-top: 44px; } .shell { padding: 0 18px; } }
    </style>
</head>
<body>
    <header class="shell">
        <a class="brand" href="<?php echo e(route('landing')); ?>">Vehicle Manager</a>
        <a class="button" href="<?php echo e(route('login')); ?>">Sign in</a>
    </header>

    <main class="shell">
        <section class="hero" aria-labelledby="page-title">
            <h1 id="page-title">Vehicle operations, organized.</h1>
            <p class="intro">
                Keep vehicle and driver records, maintenance, fuel activity, parking, and reports together
                in one vehicle-management workspace.
            </p>
            <a class="button" href="<?php echo e(route('login')); ?>">Sign in to your workspace</a>
        </section>

        <section aria-labelledby="features-title">
            <h2 id="features-title">Manage the details that keep your fleet moving</h2>
            <div class="features">
                <article class="feature">
                    <h3>Vehicles and drivers</h3>
                    <p>Keep vehicle details, assignments, driver records, and document expiry dates organized.</p>
                </article>
                <article class="feature">
                    <h3>Maintenance and fuel</h3>
                    <p>Record service work, odometer readings, fuel entries, and related costs.</p>
                </article>
                <article class="feature">
                    <h3>Parking and reports</h3>
                    <p>Track parking activity and create vehicle, maintenance, fuel, expiry, and cost reports.</p>
                </article>
            </div>
        </section>
    </main>

    <footer>
        <div class="shell">Vehicle Manager · Vehicle and fleet operations workspace</div>
    </footer>
</body>
</html>
<?php /**PATH D:\laragon\www\vehicle-app\resources\views/landing.blade.php ENDPATH**/ ?>