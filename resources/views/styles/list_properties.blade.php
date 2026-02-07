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
    .feature-card {
        cursor: pointer;
        transition: all .2s ease;
    }

    .feature-card:hover {
        border-color: #198754;
    }

    .feature-card.active {
        border-color: #198754;
        background-color: #e9f7ef;
    }

    .amenity-row {
        margin-top: 16px;
        padding-top: 14px;
        border-top: 1px solid #f1f5f9;
        display: flex;
        justify-content: space-between;
    }

    .amenity-box {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--body-text);
    }

    .amenity-box i {
        font-size: 1.1rem;
        color: var(--primary-color);
    }

    .card-clean:hover .amenity-box i {
        transform: scale(1.15);
        transition: transform 0.3s ease;
    }
    .property-mini-actions .icon-btn {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        border: 1px solid #e5e7eb;
        background: #fff;
        color: #374151;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all .2s ease;
    }

    .property-mini-actions .icon-btn:hover {
        background: #f3f4f6;
        color: #111827;
        transform: translateY(-1px);
    }

    .property-mini-actions .icon-btn i {
        font-size: 0.95rem;
    }

    .badge {
        font-size: 0.7rem;
        padding: 6px 10px;
        border-radius: 14px;
        font-weight: 500;
    }
    .ia-sticky {
        position: sticky;
        top: 100px;
    }
</style>
