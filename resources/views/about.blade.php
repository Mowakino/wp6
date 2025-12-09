<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About Us - Leaf & Spoon</title>

    <link rel="stylesheet" href="{{ asset('bootstrap-5.0.2-dist/css/bootstrap.min.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
<style>
    body {
        font-family: 'Nunito Sans', sans-serif;
        background: #f8f9fa;
        color: #333;
    }

    /* HERO SECTION */
    .hero {
        background: linear-gradient(135deg, #86A857 0%, #A3BD74 100%), url('{{ asset("images/hero.jpg") }}');
        background-size: cover;
        background-position: center;
        padding: 120px 20px;
        text-align: center;
        color: white;
        position: relative;
    }
    .hero h1 {
        font-size: 56px;
        font-weight: 800;
    }

    .hero p {
        font-size: 20px;
        margin-top: 10px;
        opacity: .95;
    }

    /* DIVIDER */
    .divider {
        width: 100px;
        height: 4px;
        background: #86A857;
        margin: 20px auto 30px auto;
        border-radius: 4px;
    }

    /* ABOUT CONTENT */
    .about-box {
        background: white;
        border-radius: 18px;
        padding: 40px 35px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.08);
        margin-bottom: 50px;
    }
    .about-text {
        font-size: 19px;
        line-height: 1.7;
        text-align: center;
    }

    /* FEATURE ICONS */
    .feature-icon {
        width: 70px;
        height: 70px;
        background: #E6F4D7;
        border-radius: 50%;
        display:flex;
        justify-content:center;
        align-items:center;
        margin: auto;
        font-size: 30px;
        color: #86A857;
    }

    /* MISSION BOX */
    .mission-card {
        background: #ffffff;
        border-radius: 14px;
        padding: 30px;
        box-shadow: 0 6px 20px rgba(0,0,0,0.07);
        height: 100%;
    }

    .mission-card h4 {
        font-weight: 700;
        color: #86A857;
    }
</style>
</head>

<body>
    <x-navbar />

    <!-- HERO -->
    <div class="hero">
        <h1>About Leaf & Spoon</h1>
        <p>Nourishing your lifestyle, one meal at a time.</p>
    </div>

    <div class="container mt-5">

        <!-- MAIN ABOUT SUMMARY -->
        <div class="about-box mx-auto">
            <h2 class="text-center fw-bold mb-4">About us</h2>
            <p class="about-text">
                Leaf & Spoon is a health-focused platform dedicated to making nutritious eating simple,
                enjoyable, and accessible. Through smart technology and curated nutritional guidance,
                we help users discover balanced recipes, create personalized meal plans, and build
                sustainable healthy habits all with clarity, convenience, and a touch of inspiration.
            </p>
        </div>

        <!-- FEATURES SECTION -->
        <h2 class="text-center fw-bold mb-4">What We Offer</h2>

        <div class="row g-4 mb-5">

            <div class="col-md-4">
                <div class="mission-card text-center">
                    <div class="feature-icon mb-3">🍲</div>
                    <h4>Nutritious Recipes</h4>
                    <p>Explore delicious meals with complete nutrition facts, tailored for your health goals.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="mission-card text-center">
                    <div class="feature-icon mb-3">📅</div>
                    <h4>Smart Meal Planning</h4>
                    <p>Build weekly meal plans that match your lifestyle, dietary needs, and preferences.</p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="mission-card text-center">
                    <div class="feature-icon mb-3">💡</div>
                    <h4>Healthy Guidance</h4>
                    <p>Learn healthier habits with curated content designed for sustainable wellness.</p>
                </div>
            </div>

        </div>

        <!-- MISSION & VISION -->
        <h2 class="text-center fw-bold mb-4">Our Mission & Vision</h2>

        <div class="row g-4 mb-5">

            <div class="col-md-6">
                <div class="mission-card">
                    <h4>Our Mission</h4>
                    <p class="mt-2">
                        To empower individuals to make better food choices by providing
                        accessible, enjoyable, and nutritionally balanced meal solutions.
                    </p>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mission-card">
                    <h4>Our Vision</h4>
                    <p class="mt-2">
                        A world where healthy eating becomes effortless, sustainable,
                        and embraced as a joyful part of everyday life.
                    </p>
                </div>
            </div>

        </div>

    </div>

    <x-footer />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="{{ asset('bootstrap-5.0.2-dist/js/bootstrap.bundle.min.js') }}"></script>
</body>
</html>
