<style>
.footer-link {
    text-decoration: none;
    color: #6c757d;
    font-size: 0.95rem;
}

.footer-link:hover {
    color: #99AF67;
}

/* Divider styling */
.divider {
    display: inline-block;
    width: 1px;
    height: 14px;
    background: #bdbdbd;
}
</style>

<footer class="bg-light text-muted py-4 mt-5 border-top">

    <div class="container d-flex justify-content-between align-items-center">

        <div class="d-flex align-items-top gap-4">

            <img src="{{ asset('vector.png') }}" alt="Leaf & Spoon Logo"
                style="width: auto; height: 45px;padding-top:10px;">

            <div>
                <p class="mb-2 mt-0 ">Healthy recipes, meal plans, and nutrition guidance.</p>
                <small>© {{ date('Y') }} Leaf & Spoon. All Rights Reserved.</small>
            </div>

        </div>

        <div class="d-flex align-items-center gap-3">

            <a href="/home" class="footer-link">Home</a>
            <span class="divider"></span>

            <a href="/recipes" class="footer-link">Recipes</a>
            <span class="divider"></span>

            <a href="/recipes/create" class="footer-link">Create</a>
            <span class="divider"></span>

            <a href="/about" class="footer-link">About</a>

        </div>

    </div>
</footer>
