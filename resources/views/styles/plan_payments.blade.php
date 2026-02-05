<style>
    .plan-card {
        cursor: pointer;
        border-radius: 1.5rem;
        transition: all 0.3s;
        border: 2px solid #e9ecef;
        background: #f9fefb;
    }

    .plan-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 1rem 2rem rgba(25, 135, 84, 0.15);
        border-color: #20c997;
    }

    .shadow-selected {
        box-shadow: 0 0 1.5rem rgba(25, 135, 84, 0.25);
        border: 2px solid #198754 !important;
    }

    .badge-featured {
        position: absolute;
        top: 15px;
        right: 15px;
        padding: 0.5rem 1rem;
        font-size: 0.8rem;
        font-weight: 600;
        background: linear-gradient(90deg, #28a745, #20c997);
        color: white;
        border-radius: 2rem;
    }

    .form-control-lg {
        padding: 1rem 1.25rem;
        border-radius: 0.75rem;
        border: 2px solid #dee2e6;
    }

    .form-control-lg:focus {
        border-color: #198754;
        box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.25);
    }

    .btn-success {
        background: linear-gradient(135deg, #198754, #20c997);
        border: none;
        border-radius: 0.75rem;
        transition: all 0.3s;
    }

    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(25, 135, 84, 0.3);
    }

    .btn-success:disabled {
        opacity: 0.7;
        transform: none;
        box-shadow: none;
    }

    .btn-outline-secondary {
        border-radius: 0.75rem;
        padding: 0.5rem 1.5rem;
        font-weight: 600;
    }

    .spinner-border {
        width: 3rem;
        height: 3rem;
    }

    @media (max-width: 768px) {
        .card-body {
            padding: 1.5rem !important;
        }

        .form-control-lg {
            padding: 0.75rem 1rem;
        }
    }
</style>
