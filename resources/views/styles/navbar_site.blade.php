<style>
    :root { --ebuy-green: #00B98E; --ebuy-dark: #1e293b; --ebuy-gray: #64748b; }

    .ebuy-header-full { background: #fff; height: 90px; display: flex; align-items: center; position: sticky; top: 0; z-index: 1000; box-shadow: 0 2px 15px rgba(0,0,0,0.03); }
    .ebuy-container-limit { width: 100%; max-width: 1400px; margin: 0 auto; padding: 0 30px; display: flex; align-items: center; justify-content: space-between; position: relative; }
    .ebuy-brand-main img { height: 60px; width: auto; }

    .ebuy-nav-center { display: flex; gap: 10px; height: 100%; position: static; }
    .ebuy-menu-item { display: flex; align-items: center; height: 100%; position: static; padding: 30px 0; }

    .ebuy-link-top { text-decoration: none !important; color: var(--ebuy-dark) !important; font-weight: 700; font-size: 14px; padding: 10px 15px; border-radius: 10px; transition: 0.3s; }

    .icon-arrow-nav { font-size: 10px; opacity: 0.5; }

    /* MEGA MENU */
    .ebuy-mega-wrapper { display: none; position: absolute; top: 90px; left: 0; right: 0; padding: 10px 30px; z-index: 2000; }
    .ebuy-menu-item:hover .ebuy-mega-wrapper { display: block; animation: ebuyFade 0.2s ease-out; }

    .mega-card { background: #fff; border-radius: 20px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); display: flex; overflow: hidden; border: 1px solid #f1f5f9; min-height: 400px; }

    .mega-sidebar { width: 280px; background: #f8fafc; padding: 20px; border-right: 1px solid #eee; }
    .sidebar-item { display: flex; align-items: center; padding: 14px; border-radius: 12px; cursor: pointer; transition: 0.2s; gap: 12px; margin-bottom: 5px; }
    .sidebar-item.active { background: #fff; box-shadow: 0 4px 10px rgba(0,0,0,0.05); color: var(--ebuy-green); }
    .sidebar-icon { width: 36px; height: 36px; background: #fff; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: var(--ebuy-green); box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
    .sidebar-text .title { font-weight: 700; font-size: 14px; display: block; }
    .sidebar-text .subtitle { font-size: 11px; color: var(--ebuy-gray); }
    .sidebar-item .arrow { margin-left: auto; opacity: 0; }
    .sidebar-item.active .arrow { opacity: 1; transform: translateX(3px); }
    .sidebar-extra { margin-top: 20px; padding: 15px; border-top: 1px solid #eee; font-size: 13px; font-weight: 600; }

    .mega-content { flex: 1; padding: 40px 50px; }
    .mega-pane { display: none; }
    .mega-pane.active { display: block; }
    .mega-grid-layout { display: grid; grid-template-columns: 1fr 1fr; gap: 40px; }
    .col-label { display: block; font-size: 11px; font-weight: 800; text-transform: uppercase; color: var(--ebuy-gray); margin-bottom: 20px; letter-spacing: 1px; }
    .mega-list-styled { list-style: none; padding: 0; }
    .mega-list-styled li { margin-bottom: 12px; }
    .mega-list-styled li a { text-decoration: none; color: var(--ebuy-dark); font-size: 15px; font-weight: 500; }
    .mega-list-styled li a:hover { color: var(--ebuy-green); padding-left: 5px; transition: 0.2s; }
    .view-all-item { margin-top: 20px; border-top: 1px solid #f1f5f9; padding-top: 15px; }
    .view-all-item a { color: var(--ebuy-green) !important; font-weight: 700 !important; font-size: 13px !important; }

    .mega-feature { width: 260px; padding: 30px; background: #fafafa; border-left: 1px solid #f1f5f9; display: flex; align-items: center; }
    .feature-card { background: var(--ebuy-green); border-radius: 15px; padding: 25px 20px; color: #fff; text-align: center; }
    .feature-card h6 { font-weight: 800; margin-bottom: 10px; }
    .feature-card p { font-size: 12px; opacity: 0.9; margin-bottom: 15px; }
    .btn-feature { display: inline-block; background: #fff; color: var(--ebuy-green); padding: 8px 15px; border-radius: 50px; text-decoration: none; font-weight: 700; font-size: 12px; }

    .ebuy-actions-right { display: flex; align-items: center; gap: 12px; }
    .btn-ebuy-outline { border: 2px solid var(--ebuy-green); color: var(--ebuy-green); padding: 10px 20px; border-radius: 50px; font-weight: 700; text-decoration: none; font-size: 13px; transition: 0.3s; }
    .btn-ebuy-outline:hover { background: var(--ebuy-green); color: #fff; }
    .btn-ebuy-solid { background: var(--ebuy-green); color: #fff; padding: 10px 25px; border-radius: 50px; font-weight: 700; text-decoration: none; font-size: 13px; border: 2px solid var(--ebuy-green); }

    /* .ebuy-lang-picker { position: relative; background: #f1f5f9; padding: 10px 15px; border-radius: 50px; font-weight: 700; color: var(--ebuy-green); font-size: 12px; cursor: pointer; } */
    .lang-dropdown { display: none; position: absolute; top: 110%; right: 0; background: #fff; box-shadow: 0 10px 20px rgba(0,0,0,0.1); border-radius: 10px; overflow: hidden; min-width: 120px; }
    /* .ebuy-lang-picker:hover .lang-dropdown { display: block; } */
    .lang-dropdown a { display: block; padding: 10px; text-decoration: none; color: #333; }

    .ebuy-sub-link:hover {
        background: #f0fdf4 !important;
        color: #00B98E !important;
    }

    .ebuy-lang-picker {
        position: relative;
        background: #f4f7f6 !important;
        padding: 10px 18px !important;
        border-radius: 50px;
        cursor: pointer;
    }

    .lang-pill-row {
        display: flex !important;
        flex-direction: row !important;
        align-items: center !important;
        gap: 8px !important;
        white-space: nowrap !important;
        color: #00B98E;
        font-weight: 800;
    }

    .lang-drop-box {
        display: none;
        position: absolute;
        top: 110%;
        right: 0;
        background: white;
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        border-radius: 12px;
        min-width: 130px;
        overflow: hidden;
    }

    .ebuy-lang-picker:hover .lang-drop-box { display: block; }

    .lang-drop-box a {
        display: block;
        padding: 10px 20px;
        text-decoration: none;
        color: #333;
        font-size: 13px;
    }


    @keyframes ebuyFade { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

    /* Estilos de otra plantila */
    .ebuy-link-top:hover, .ebuy-link-top.is-active {
        color: #00B98E !important;
    }
    .ebuy-dropdown-float.ebuy-grid-menu {
        display: none;
        position: absolute;
        top: 100px;
        left: 50%;
        transform: translateX(-50%);
        width: 95%;
        max-width: 1200px;
        background: #ffffff !important;
        box-shadow: 0 20px 50px rgba(0,0,0,0.15) !important;
        border-radius: 24px;
        padding: 30px;
        border: 1px solid #f0f0f0;
        z-index: 10000;
    }

    .ebuy-menu-item:hover .ebuy-grid-menu {
        display: block;
        animation: ebuyFadeUp 0.3s ease-out;
    }
    .grid-menu-inner {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 15px;
    }
    .ebuy-grid-link {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 15px;
        text-decoration: none !important;
        border-radius: 15px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid transparent;
        background: transparent;
    }

    .ebuy-grid-link:hover {
        background: #f0fdf4 !important;
        border-color: #00B98E;
        transform: translateY(-4px);
    }

    .ebuy-grid-link .icon-box {
        width: 45px;
        height: 45px;
        background: #f4f7f6;
        color: #00B98E;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        font-size: 18px;
        transition: 0.3s;
    }

    .ebuy-grid-link:hover .icon-box {
        background: #00B98E;
        color: #ffffff;
    }

    .ebuy-grid-link .text-box {
        display: flex;
        flex-direction: column;
    }

    .ebuy-grid-link .title {
        color: #1e293b;
        font-weight: 700;
        font-size: 15px;
        margin-bottom: 2px;
    }

    .ebuy-grid-link .desc {
        color: #94a3b8;
        font-size: 12px;
        font-weight: 400;
    }

    .ebuy-actions-right { display: flex; align-items: center; gap: 15px; }

    .btn-ebuy-outline, .btn-ebuy-solid {
        height: 48px;
        padding: 0 25px;
        border-radius: 50px;
        display: flex;
        align-items: center;
        font-weight: 700;
        text-decoration: none !important;
    }

    .btn-ebuy-outline { border: 2px solid #00B98E; color: #00B98E; background: transparent; }
    .btn-ebuy-outline:hover { background: #00B98E !important; color: #fff !important; }

    .btn-ebuy-solid { background: #00B98E !important; color: #fff !important; border: 2px solid #00B98E; }
    .btn-ebuy-solid:hover { background: #0e2e50 !important; border-color: #0e2e50 !important; }

    @keyframes ebuyFadeUp {
        from { opacity: 0; transform: translate(-50%, 15px); }
        to { opacity: 1; transform: translate(-50%, 0); }
    }

</style>
