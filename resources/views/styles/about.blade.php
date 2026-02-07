<style>
    .about-section {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-radius: 50px;
        margin: 20px;
        padding: 80px 0;
    }
    .img-stack {
        position: relative;
        padding: 20px;
    }
    .img-stack img {
        border-radius: 20px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.1);
    }
    .floating-card {
        position: absolute;
        bottom: -20px;
        right: 0;
        background: #ffffff;
        padding: 25px;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        max-width: 250px;
        border-left: 5px solid #00B98E;
        animation: upDown 3s ease-in-out infinite;
    }
    @keyframes upDown {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
    .feature-box {
        background: white;
        padding: 20px;
        border-radius: 15px;
        transition: 0.3s;
        border: 1px solid rgba(0,0,0,0.05);
    }
    .feature-box:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.05);
    }
    .ebuy-gradient-text {
        background: linear-gradient(45deg, #00B98E, #2ecc71);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-weight: 800;
    }
</style>
