<?php
$pageTitle = $pageTitle ?? 'Mobile Money';
$pageSubtitle = $pageSubtitle ?? 'Simulation opérateur';
$active = $active ?? '';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($pageTitle) ?></title>
    <style>
        :root {
            --bg: #0f172a;
            --panel: #111827;
            --card: #1f2937;
            --card-soft: #243244;
            --accent: #22c55e;
            --accent-2: #38bdf8;
            --text: #e5e7eb;
            --muted: #9ca3af;
            --danger: #f87171;
            --warning: #fbbf24;
            --border: rgba(255,255,255,.08);
            --shadow: 0 18px 40px rgba(0,0,0,.28);
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: Inter, system-ui, -apple-system, Segoe UI, Roboto, Arial, sans-serif;
            background:
                radial-gradient(circle at top left, rgba(34,197,94,.20), transparent 28%),
                radial-gradient(circle at top right, rgba(56,189,248,.16), transparent 26%),
                linear-gradient(180deg, #0b1020 0%, #0f172a 100%);
            color: var(--text);
            min-height: 100vh;
        }
        a { color: inherit; text-decoration: none; }
        .shell {
            max-width: 1200px;
            width: min(1200px, calc(100% - 24px));
            margin: 0 auto;
            padding: 20px 0 24px;
            display: flex;
            flex-direction: column;
            gap: 16px;
        }
        .topbar {
            display: flex; justify-content: space-between; align-items: center; gap: 16px;
            padding: 16px 18px; background: rgba(17,24,39,.78); border: 1px solid var(--border);
            border-radius: 22px; box-shadow: var(--shadow); backdrop-filter: blur(10px);
            position: sticky; top: 16px; z-index: 10;
        }
        .brand { display: flex; flex-direction: column; gap: 2px; }
        .brand strong { font-size: 1.1rem; letter-spacing: .2px; }
        .brand span { color: var(--muted); font-size: .92rem; }
        .nav { display: flex; flex-wrap: wrap; gap: 10px; }
        .nav a {
            padding: 10px 14px; border-radius: 999px; background: rgba(255,255,255,.04);
            border: 1px solid var(--border); color: var(--text); font-size: .95rem;
        }
        .nav a.active, .nav a:hover { background: linear-gradient(135deg, var(--accent), #16a34a); color: #04110a; }
        .hero {
            margin: 0; padding: 24px 24px 22px; border-radius: 24px;
            background: linear-gradient(135deg, rgba(34,197,94,.18), rgba(56,189,248,.12));
            border: 1px solid rgba(255,255,255,.08); box-shadow: var(--shadow);
        }
        .hero h1 { margin: 0 0 10px; font-size: clamp(1.5rem, 4vw, 2.8rem); }
        .hero p { margin: 0; color: var(--text); opacity: .92; max-width: 900px; line-height: 1.6; }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px; align-items: start; }
        .card {
            background: rgba(17,24,39,.85); border: 1px solid var(--border); border-radius: 22px;
            padding: 20px; box-shadow: var(--shadow); display: flex; flex-direction: column; gap: 12px;
        }
        .card h2, .card h3 { margin-top: 0; }
        .muted { color: var(--muted); }
        .flash {
            margin: 14px 0 0; padding: 14px 16px; border-radius: 16px; border: 1px solid var(--border);
        }
        .flash.success { background: rgba(34,197,94,.15); color: #bbf7d0; }
        .flash.error { background: rgba(248,113,113,.15); color: #fecaca; }
        .stats {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 14px; margin-top: 16px;
        }
        .stat {
            padding: 18px; border-radius: 20px; background: linear-gradient(180deg, rgba(255,255,255,.05), rgba(255,255,255,.02));
            border: 1px solid var(--border);
        }
        .stat small { display: block; color: var(--muted); margin-bottom: 8px; }
        .stat strong { font-size: 1.4rem; }
        form { display: grid; gap: 12px; }
        label { display: grid; gap: 8px; color: var(--text); }
        input, button, select, textarea {
            font: inherit; border-radius: 14px; border: 1px solid var(--border); padding: 12px 14px;
        }
        input, textarea, select {
            background: rgba(15,23,42,.88); color: var(--text); outline: none;
        }
        input:focus, textarea:focus, select:focus { border-color: rgba(56,189,248,.7); box-shadow: 0 0 0 3px rgba(56,189,248,.12); }
        button {
            background: linear-gradient(135deg, var(--accent), #16a34a); color: #04110a; font-weight: 700; cursor: pointer;
            transition: transform .15s ease, filter .15s ease;
        }
        button:hover { transform: translateY(-1px); filter: brightness(1.03); }
        .secondary-btn { background: linear-gradient(135deg, var(--accent-2), #0ea5e9); color: #02131d; }
        .danger-btn { background: linear-gradient(135deg, #fb7185, #ef4444); color: #1f0a0a; }
        .actions { display: grid; grid-template-columns: 1fr; gap: 10px; }
        .actions a { width: 100%; text-align: center; }
        .table-wrap { overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 12px 10px; border-bottom: 1px solid var(--border); text-align: left; }
        th { color: #cbd5e1; font-weight: 700; background: rgba(255,255,255,.03); }
        tr:hover td { background: rgba(255,255,255,.02); }
        .footer { color: var(--muted); text-align: center; padding: 18px 0 4px; font-size: .95rem; }
        .split { display: grid; grid-template-columns: 1.1fr .9fr; gap: 16px; align-items: start; }
        @media (max-width: 900px) {
            .topbar, .split { grid-template-columns: 1fr; display: grid; }
            .nav { width: 100%; }
            .nav a { flex: 1 1 calc(50% - 8px); text-align: center; }
        }
        @media (max-width: 720px) {
            .shell { width: min(100%, calc(100% - 16px)); padding: 12px 0 18px; }
            .topbar { padding: 14px; }
            .hero { padding: 18px; }
            .card { padding: 16px; }
            .stats { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
<div class="shell">
    <header class="topbar">
        <div class="brand">
            <strong>Mobile Money Simulator</strong>
            <span><?= esc($pageSubtitle) ?></span>
        </div>
        <nav class="nav">
            <a class="<?= $active === 'home' ? 'active' : '' ?>" href="/">Connexion client</a>
            <a class="<?= $active === 'inscription' ? 'active' : '' ?>" href="/inscription">Inscription</a>
            <a class="<?= $active === 'client' ? 'active' : '' ?>" href="/client/dashboard">Espace client</a>
            <a class="<?= $active === 'operator' ? 'active' : '' ?>" href="/login/operateur">Espace opérateur</a>
        </nav>
    </header>
    <section class="hero">
        <h1><?= esc($pageTitle) ?></h1>
        <p>Interface unique pour simuler un portefeuille mobile money : connexion par numéro, dépôts, retraits, transferts et historique.</p>
    </section>