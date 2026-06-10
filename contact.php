<?php

require_once 'layout/header.php';

$success_msg = "";
$error_msg = "";

// Form එක Submit වූ විට ක්‍රියාත්මක වන කොටස
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $service = $_POST['service'];
    $event_date = $_POST['event_date'];
    $venue = trim($_POST['venue']);
    $message = trim($_POST['message']);

    if (!empty($name) && !empty($email) && !empty($phone) && !empty($service) && !empty($event_date) && !empty($venue)) {
        try {
            $stmt = $conn->prepare("INSERT INTO contact_messages (name, email, phone, service, event_date, venue, message) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$name, $email, $phone, $service, $event_date, $venue, $message]);
            $success_msg = "Thank you! Your inquiry has been sent successfully. We will contact you soon.";
        } catch(PDOException $e) {
            $error_msg = "Something went wrong: " . $e->getMessage();
        }
    } else {
        $error_msg = "Please fill in all required fields.";
    }
}

// Packages පිටුවෙන් පැකේජයක් තෝරාගෙන ආවේ නම් එය පණිවිඩය තුළට ඇතුළත් කිරීම
$prefilled_message = "";
if (isset($_GET['package'])) {
    $package_name = htmlspecialchars($_GET['package']);
    $prefilled_message = "Hi Lumos Studio, I am interested in inquiring about the '" . $package_name . "' package for my event. Please let me know your availability.";
}
?>

<style>
    body {
        background-color: #ffffff;
        color: #333;
    }
    .page-title {
        font-weight: 300;
        letter-spacing: 5px;
        text-transform: uppercase;
        margin-bottom: 50px;
        text-align: center;
        font-family: 'Times New Roman', serif;
    }
    
    /* Contact Form Styling */
    .contact-card {
        border: 1px solid #eaeaea;
        border-radius: 0; /* Minimalist Square corners */
        background: #ffffff;
    }
    
    .form-control, .form-select {
        border-radius: 0;
        border: 1px solid #ccc;
        padding: 12px;
        font-size: 0.95rem;
    }
    .form-control:focus, .form-select:focus {
        border-color: #000;
        box-shadow: none;
    }
    
    .btn-submit {
        background-color: #000000;
        color: #ffffff;
        border: 1px solid #000000;
        border-radius: 0;
        padding: 12px;
        letter-spacing: 2px;
        text-transform: uppercase;
        font-size: 0.9rem;
        transition: 0.3s;
    }
    .btn-submit:hover {
        background-color: #ffffff;
        color: #000000;
    }
    
    /* Contact Details Column */
    .contact-details h4 {
        font-family: 'Times New Roman', serif;
        font-weight: 400;
        letter-spacing: 1px;
    }
    .social-links a {
        color: #333;
        font-size: 1.2rem;
        margin-right: 15px;
        text-decoration: none;
        transition: 0.3s;
    }
    .social-links a:hover {
        color: #888;
    }
</style>

<div class="container pb-5" style="padding-top: 140px;">
    <h1 class="page-title">CONTACT US</h1>
    <p class="text-center text-muted mb-5" style="letter-spacing: 1px; font-weight: 300;">Let's discuss and craft your beautiful story together.</p>

    <div class="row justify-content-center">
        <!-- 1. Form එක පෙන්වන Column එක -->
        <div class="col-lg-7 mb-5">
            <div class="card contact-card shadow-sm p-4">
                
                <!-- Alerts (සාර්ථකද නැද්ද යන්න පෙන්වීමට) -->
                <?php if($success_msg != ""): ?>
                    <div class="alert alert-success border-0 rounded-0 py-3 mb-4"><?= $success_msg ?></div>
                <?php endif; ?>
                <?php if($error_msg != ""): ?>
                    <div class="alert alert-danger border-0 rounded-0 py-3 mb-4"><?= $error_msg ?></div>
                <?php endif; ?>

                <form action="contact.php" method="POST">
                    <div class="row">
                        <!-- Name -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label small text-uppercase">Your Name *</label>
                            <input type="text" name="name" class="form-control" placeholder="e.g. Kamal Perera" required>
                        </div>
                        <!-- Email -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label small text-uppercase">Email Address *</label>
                            <input type="email" name="email" class="form-control" placeholder="e.g. kamal@gmail.com" required>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Phone Number -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label small text-uppercase">Telephone Number *</label>
                            <input type="tel" name="phone" class="form-control" placeholder="e.g. 0771234567" required>
                        </div>
                        <!-- Service Type -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label small text-uppercase">Service *</label>
                            <select name="service" class="form-select" required>
                                <option value="" disabled selected>Choose a Service</option>
                                <option value="Wedding">Wedding Photography</option>
                                <option value="Engagement">Engagement</option>
                                <option value="Casual Session">Casual Session / Pre Shoot</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Date -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label small text-uppercase">Event Date *</label>
                            <input type="date" name="event_date" class="form-control" required>
                        </div>
                        <!-- Venue -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label small text-uppercase">Venue (Location) *</label>
                            <input type="text" name="venue" class="form-control" placeholder="e.g. Kingsbury Hotel, Colombo" required>
                        </div>
                    </div>

                    <!-- Message -->
                    <div class="mb-4">
                        <label class="form-label small text-uppercase">Your Message (Optional)</label>
                        <!-- Pre-filled message එකක් ඇත්නම් එය මෙහි පෙන්වයි -->
                        <textarea name="message" class="form-control" rows="5" placeholder="Tell us more about your special day..."><?= $prefilled_message ?></textarea>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-submit w-100">Send Inquiry</button>
                </form>
            </div>
        </div>

        <!-- 2. Studio details පෙන්වන Column එක -->
        <div class="col-lg-4 px-lg-5 contact-details">
            <div class="mb-5">
                <h4 class="mb-3">Lumos Studio</h4>
                <p class="text-muted small">We are visual storytellers based in Sri Lanka, capturing emotions that last a lifetime.</p>
            </div>
            
            <div class="mb-4">
                <h6 class="text-uppercase small fw-bold">Office Address</h6>
                <p class="text-muted">26 mile post,Bubula,kuruvithanna,Mahiyanganaya,Sri Lanka</p>
            </div>

            <div class="mb-4">
                <h6 class="text-uppercase small fw-bold">Call / WhatsApp</h6>
                <p class="text-muted">+94 70 137 6989<br>+94 75 838 5027</p>
            </div>

            <div class="mb-4">
                <h6 class="text-uppercase small fw-bold">General Email</h6>
                <p class="text-muted">lumosstudio.lk@gmail.com</p>
            </div>

            <div class="mb-4">
                <h6 class="text-uppercase small fw-bold">Connect With Us</h6>
                <div class="social-links mt-2">
                    <a href="https://www.facebook.com/profile.php?id=61550491520210">Facebook</a>
                    <a href="https://www.tiktok.com/@lumosstudio.lk?lang=en">Tiktok</a>
                    <a href="https://api.whatsapp.com/send?phone=94701376989&text=Hello%2C%20I%20would%20like%20to%20make%20a%20booking%20for%20your%20Wedding%20Photography%20service.%20Please%20share%20the%20available%20packages%20and%20pricing%20details.">WhatsApp</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once 'layout/footer.php'; ?>