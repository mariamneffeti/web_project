<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title >Entreprisa</title>
  <link rel="stylesheet" href="../../node_modules/bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    
    <a class="navbar-brand" href="#" >
      <h1 id="title"></h1>
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link active" href="#">Home</a></li>
        <li class="nav-item"><a class="nav-link" href="#" id="aboutBtn">About</a></li>
        <li class="nav-item"><a class="nav-link" href="#" id="servicesBtn">Services</a></li>
        
      </ul>
    </div>
  </div>
</nav>

<section class="hero text-center py-5">
  <div class="container">
    <h1 class="mb-3">Welcome to Our Website</h1>
    <p class="lead mb-4">
      Our website aims to create a structured platform that connects companies,
      employees, and users in one system.
    </p>
    <div class="d-flex justify-content-center gap-3">
      <a href="../login/login.php" class="btn btn-primary btn-lg">Login</a>
      <a href="../register/register.php" class="btn btn-outline-primary btn-lg">Register</a>
    </div>
  </div>
</section>


<section class="services py-5" id="services">
  <div class="container text-center" class="service-card">
    <h2 class="mb-4">Services</h2>
    <div class="row g-4">
      <div class="col-md-4">
        <div class="card h-100">
          <div class="service-card" >
            <h5 class="card-title">Company Services</h5>
            <p class="card-text">Tools and services for companies.</p>
            <ul>
                    <li>Clients & Employees Management</li>
                    <li>Sales Management</li>
                    <li>Recruitment</li>
            </ul>
          </div>
        </div>
      </div>
      <div class="col-md-4" class="service-card">
        <div class="card h-100">
          <div class="service-card" >
            <h5 class="card-title">Employee Services</h5>
            <p class="card-text">Employee management and resources.</p>
            <ul>
                    <li>Clients Management</li>
                    <li>Sales Management</li>
                    <li>Services</li>
            </ul>
          </div>
        </div>
      </div>
      <div class="col-md-4" >
        <div class="card h-100">
          <div class="service-card" >
            <h5 class="card-title">Client / Applicant</h5>
            <p class="card-text">Applications and user services.</p>
            <ul>
                    <li>Articles Feed</li>
                    <li>Company Offers</li>
                    <li>CV Management</li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>


<footer class="bg-dark text-white text-center py-4">
  <section id="contact">
    <h2>Contact</h2>
  <p class="mb-1">Email: contact@mail.com</p>
  <p class="mb-0">Phone: +216 24 456 789</p>
  </section>
</footer>

<button id="backToTop">⇧ Top</button>
<script>
  //console.log(document.getElementById("aboutBtn"));
  const btn=document.getElementById("aboutBtn");
  const contactSection=document.getElementById("contact");
  btn.addEventListener("click",(e)=>{
    e.preventDefault();
    contactSection.scrollIntoView({
      behavior: "smooth"
    });
  });

const servicesBtn = document.getElementById("servicesBtn");
const servicesSection = document.getElementById("services");

servicesBtn.addEventListener("click", (e) => {
  e.preventDefault();

  // Highlight effect
  servicesSection.classList.remove("highlight");
  void servicesSection.offsetWidth; // restart animation
  servicesSection.classList.add("highlight");

  // Cards animation
  const cards = document.querySelectorAll(".service-card");

  cards.forEach((card, index) => {
    card.classList.remove("show"); // reset

    setTimeout(() => {
      card.classList.add("show");
    }, index * 150);
  });
});

const backToTop=document.getElementById("backToTop");
window.addEventListener("scroll",()=>{
  if(window.scrollY>200){
    backToTop.style.display="block";
  }
  else{
    backToTop.style.display="none";
  }
});

backToTop.addEventListener("click",()=>{
  window.scrollTo(
{
  top : 0,
  behavior : "smooth"
}
  );
});

const text = "Entreprisa";
let i=0;
function typeEffect(){
  if(i<text.length){
    document.getElementById("title").textContent+=text.charAt(i);
    i++;
    setTimeout(typeEffect,100);
  }
}
typeEffect();
</script>
</body>
</html>
