<style>
    :root {
        --primary-color: #00B98E;
        --dark-text: #1A202C;
        --body-text: #64748B;
    }

    /* Títulos limpios */
    .section-title {
        font-weight: 850;
        color: var(--dark-text);
        letter-spacing: -1.5px;
        line-height: 1.1;
    }

    /* Tabs minimalistas (Pills) */
    .nav-pills-custom .nav-link {
        color: var(--body-text);
        font-weight: 600;
        padding: 10px 25px;
        border-radius: 15px;
        transition: all 0.3s ease;
        border: 1px solid #f0f0f0;
        background: #fff;
        margin-right: 10px;
    }

    .nav-pills-custom .nav-link.active {
        background-color: var(--primary-color) !important;
        color: white !important;
        border-color: var(--primary-color);
        box-shadow: 0 10px 20px rgba(0, 185, 142, 0.2);
    }

    /* Tarjeta Estilo Premium Blanco */
    .card-clean {
        border: none;
        background: #ffffff;
        border-radius: 30px;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid #f1f5f9; /* Un borde casi invisible */
    }

    .card-clean:hover {
        transform: translateY(-12px);
        box-shadow: 0 30px 60px rgba(0, 0, 0, 0.08);
        border-color: var(--primary-color);
    }

    /* Contenedor de imagen con bordes orgánicos */
    .img-frame {
        position: relative;
        padding: 12px; /* Espacio que crea el efecto de marco blanco */
    }

    .img-frame img {
        border-radius: 24px;
        width: 100%;
        object-fit: cover;
        height: 240px;
    }

    /* Etiquetas flotantes */
    .floating-tag {
        position: absolute;
        top: 25px;
        right: 25px;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(5px);
        padding: 6px 16px;
        border-radius: 12px;
        font-weight: 700;
        font-size: 0.8rem;
        color: var(--dark-text);
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }

    /* Amenidades en diseño horizontal limpio */
    .amenity-row {
        padding: 15px 20px;
        border-top: 1px solid #f8fafc;
        display: flex;
        justify-content: space-between;
    }

    .amenity-box {
        font-size: 0.85rem;
        color: var(--body-text);
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .amenity-box i {
        color: var(--primary-color);
        font-size: 1rem;
    }

    /* Precio destacado */
    .price-tag {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--primary-color);
        margin-bottom: 5px;
    }
</style>
