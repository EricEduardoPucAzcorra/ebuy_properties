{{-- <style>
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

</style> --}}


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
        position: relative;
    }

    .ebuy-brand-main img {
        height: 85px !important;
        width: auto;
    }

    .ebuy-nav-center {
        display: flex;
        gap: 10px;
        align-items: center;
        position: static;
    }

    .ebuy-menu-item {
        position: static;
        padding: 35px 0;
    }

    .ebuy-link-top {
        text-decoration: none !important;
        color: #333 !important;
        font-weight: 700 !important;
        font-size: 14px;
        text-transform: uppercase;
        padding: 10px 15px !important;
        border-radius: 8px;
        transition: 0.3s;
    }

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

    .ebuy-lang-picker {
        position: relative;
        background: #f4f7f6 !important;
        padding: 10px 18px !important;
        border-radius: 50px;
        cursor: pointer;
    }

    .lang-pill-row {
        display: flex;
        align-items: center;
        gap: 8px;
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
</style>
