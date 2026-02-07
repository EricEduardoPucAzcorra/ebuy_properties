<style>
    .ebuy-header-full {
        background: #ffffff !important;
        height: 100px !important;
        display: flex !important;
        align-items: center !important;
        position: sticky;
        top: 0;
        z-index: 9999;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
    }

    .ebuy-container-limit {
        width: 100%;
        max-width: 1400px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 30px;
    }

    .ebuy-brand-main img {
        height: 85px !important;
        width: auto;
    }

    .ebuy-nav-center { display: flex; gap: 20px; align-items: center; }
    .ebuy-menu-item { position: relative; padding: 35px 0; }

    .ebuy-link-top {
        text-decoration: none !important;
        color: #333 !important;
        font-weight: 700 !important;
        font-size: 14px;
        text-transform: uppercase;
        background: none !important;
        padding: 10px 15px !important;
        border-radius: 8px;
        transition: 0.3s;
    }
    .ebuy-link-top:hover, .ebuy-link-top.is-active {
        color: #00B98E !important;
        background: transparent !important;
    }

    .ebuy-dropdown-float {
        display: none;
        position: absolute;
        top: 90%;
        left: 0;
        background: #ffffff !important;
        min-width: 220px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important;
        border-radius: 12px;
        padding: 10px 0;
        z-index: 10000;
        /* border-top: 3px solid #00B98E; */
    }

    .ebuy-menu-item:hover .ebuy-dropdown-float { display: block; }

    .ebuy-sub-link {
        display: block !important;
        padding: 12px 20px !important;
        color: #444 !important;
        text-decoration: none !important;
        font-size: 14px;
        background: transparent !important;
    }

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

    .btn-ebuy-outline { border: 2px solid #00B98E; color: #00B98E; background: transparent !important; }
    .btn-ebuy-outline:hover { background: #00B98E !important; color: #fff !important; }

    .btn-ebuy-solid { background: #00B98E !important; color: #fff !important; border: 2px solid #00B98E; }
    .btn-ebuy-solid:hover { background: #0e2e50 !important; border-color: #0e2e50 !important; }

</style>
