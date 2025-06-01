<!-- filepath: f:\UGM\cloudcomputing\cloudcomputing_project\resources\views\about.blade.php -->
@extends('layouts.app')

@section('title', 'About Us')

@section('content')
<div class="container">
    <!-- Hero Section -->
    <div class="neo-card mb-5">
        <div class="card-body p-5">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold mb-4">We're Democratizing <span class="text-primary-gradient">Cloud Computing</span></h1>
                    <p class="lead mb-4">Our mission is to make powerful cloud processing accessible to everyone through a fair, transparent token-based system.</p>
                    <div class="d-flex gap-3">
                        <a href="{{ route('features') }}" class="neo-btn">
                            <i class="fas fa-rocket me-2"></i> Explore Our Features
                        </a>
                        <a href="{{ route('pricing') }}" class="neo-btn btn-secondary">
                            <i class="fas fa-coins me-2"></i> View Pricing
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 mt-4 mt-lg-0">
                    <div class="position-relative">
                        <img src="{{ asset('images/4743634.png') }}" alt="Cloud Computing" class="img-fluid" style="border: 3px solid var(--secondary); border-radius: 8px; box-shadow: 5px 5px 0 var(--shadow-color); width: 75%;">
                        <div class="position-absolute" style="top: -20px; right: -20px; border: 3px solid var(--secondary); border-radius: 8px; padding: 10px 15px; box-shadow: 4px 4px 0 var(--shadow-color);">
                            <div class="fw-bold">Est. 2023</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Our Story -->
    <div class="mb-5">
        <div class="text-center mb-4">
            <h2 class="display-5 fw-bold mb-3">Our Story</h2>
            <p class="lead col-lg-8 mx-auto">From a simple idea to a platform serving thousands of users worldwide</p>
        </div>

        <div class="neo-card">
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-4 mb-4 mb-md-0">
                        <div class="ratio ratio-1x1 mb-3">
                            <div class="neo-card d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #a1c4fd, #c2e9fb);">
                                <div class="text-center p-4">
                                    <i class="fas fa-lightbulb fa-3x mb-3"></i>
                                    <h4 class="fw-bold">The Idea</h4>
                                    <p class="mb-0">We saw a gap in accessible cloud computing resources</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4 mb-md-0">
                        <div class="ratio ratio-1x1 mb-3">
                            <div class="neo-card d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #fccb90, #d57eeb);">
                                <div class="text-center p-4">
                                    <i class="fas fa-code fa-3x mb-3"></i>
                                    <h4 class="fw-bold">Development</h4>
                                    <p class="mb-0">Built by developers who understand user needs</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="ratio ratio-1x1 mb-3">
                            <div class="neo-card d-flex align-items-center justify-content-center" style="background: linear-gradient(135deg, #84fab0, #8fd3f4);">
                                <div class="text-center p-4">
                                    <i class="fas fa-rocket fa-3x mb-3"></i>
                                    <h4 class="fw-bold">Launch</h4>
                                    <p class="mb-0">Serving thousands of users with reliable cloud services</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <p>CloudComputing started in 2023 when a group of cloud engineers identified a common problem: powerful computing resources were only accessible to large enterprises with big budgets.</p>

                    <p>We believed everyone should have access to cloud computing power without complex pricing models or long-term commitments. Our token-based system was designed to be straightforward, fair, and scalable for users of all sizes.</p>

                    <p>After months of development and testing, we launched our platform with a simple promise: pay only for what you use, with complete transparency and no hidden costs. Today, thousands of users rely on our platform for their cloud computing needs.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Meet the Team -->
    <div class="mb-5">
        <div class="text-center mb-4">
            <h2 class="display-5 fw-bold mb-3">Meet Our Team</h2>
            <p class="lead col-lg-8 mx-auto">The talented people behind CloudComputing</p>
        </div>

        <div class="d-flex justify-content-center">
            <div class="row g-4 justify-content-center" style="max-width: 1200px;">
                <div class="col-lg-3 col-md-6">
                    <div class="neo-card h-100">
                        <div style="height: 8px; background: linear-gradient(90deg, #ff9a9e, #fad0c4);"></div>
                        <div class="card-body p-4 text-center">
                            <div class="mb-3 mx-auto overflow-hidden" style="width: 120px; height: 120px; border-radius: 50%; border: 3px solid var(--secondary); box-shadow: 4px 4px 0 var(--shadow-color);">
                                <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="CEO" class="img-fluid">
                            </div>
                            <h5 class="fw-bold mb-1">Alex Johnson</h5>
                            <p class="text-muted mb-3">CEO & Founder</p>
                            <p class="small mb-3">Former cloud architect with 15+ years of experience in AWS and Azure infrastructures.</p>
                            <div class="d-flex justify-content-center gap-2">
                                <a href="#" class="btn btn-sm" style="border: 2px solid var(--secondary); border-radius: 50%; width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; box-shadow: 2px 2px 0 var(--shadow-color);">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="#" class="btn btn-sm" style="border: 2px solid var(--secondary); border-radius: 50%; width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; box-shadow: 2px 2px 0 var(--shadow-color);">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                                <a href="#" class="btn btn-sm" style="border: 2px solid var(--secondary); border-radius: 50%; width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; box-shadow: 2px 2px 0 var(--shadow-color);">
                                    <i class="fab fa-github"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="neo-card h-100">
                        <div style="height: 8px; background: linear-gradient(90deg, #a1c4fd, #c2e9fb);"></div>
                        <div class="card-body p-4 text-center">
                            <div class="mb-3 mx-auto overflow-hidden" style="width: 120px; height: 120px; border-radius: 50%; border: 3px solid var(--secondary); box-shadow: 4px 4px 0 var(--shadow-color);">
                                <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="CTO" class="img-fluid">
                            </div>
                            <h5 class="fw-bold mb-1">Sarah Chen</h5>
                            <p class="text-muted mb-3">CTO</p>
                            <p class="small mb-3">Full-stack developer with a passion for building scalable, user-friendly systems.</p>
                            <div class="d-flex justify-content-center gap-2">
                                <a href="#" class="btn btn-sm" style="border: 2px solid var(--secondary); border-radius: 50%; width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; box-shadow: 2px 2px 0 var(--shadow-color);">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="#" class="btn btn-sm" style="border: 2px solid var(--secondary); border-radius: 50%; width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; box-shadow: 2px 2px 0 var(--shadow-color);">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                                <a href="#" class="btn btn-sm" style="border: 2px solid var(--secondary); border-radius: 50%; width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; box-shadow: 2px 2px 0 var(--shadow-color);">
                                    <i class="fab fa-github"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="neo-card h-100">
                        <div style="height: 8px; background: linear-gradient(90deg, #fccb90, #d57eeb);"></div>
                        <div class="card-body p-4 text-center">
                            <div class="mb-3 mx-auto overflow-hidden" style="width: 120px; height: 120px; border-radius: 50%; border: 3px solid var(--secondary); box-shadow: 4px 4px 0 var(--shadow-color);">
                                <img src="{{ asset('images/team/hafiz.jpg') }}" alt="Muhamad Hafiz Saputra" alt="Lead Developer" class="img-fluid">
                            </div>
                            <h5 class="fw-bold mb-1">Muhamad Hafiz Saputra</h5>
                            <p class="text-muted mb-3">Scaling instance</p>
                            <p class="small mb-3">It always seems impossible until it's done.</p>
                            <div class="d-flex justify-content-center gap-2">
                                <a href="#" class="btn btn-sm" style="border: 2px solid var(--secondary); border-radius: 50%; width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; box-shadow: 2px 2px 0 var(--shadow-color);">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="#" class="btn btn-sm" style="border: 2px solid var(--secondary); border-radius: 50%; width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; box-shadow: 2px 2px 0 var(--shadow-color);">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                                <a href="#" class="btn btn-sm" style="border: 2px solid var(--secondary); border-radius: 50%; width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; box-shadow: 2px 2px 0 var(--shadow-color);">
                                    <i class="fab fa-github"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="neo-card h-100">
                        <div style="height: 8px; background: linear-gradient(90deg, #84fab0, #8fd3f4);"></div>
                        <div class="card-body p-4 text-center">
                            <div class="mb-3 mx-auto overflow-hidden" style="width: 120px; height: 120px; border-radius: 50%; border: 3px solid var(--secondary); box-shadow: 4px 4px 0 var(--shadow-color);">
                                <img src="https://randomuser.me/api/portraits/women/28.jpg" alt="UX Designer" class="img-fluid">
                            </div>
                            <h5 class="fw-bold mb-1">Emma Wilson</h5>
                            <p class="text-muted mb-3">UX Designer</p>
                            <p class="small mb-3">Creating intuitive user experiences with a focus on accessibility and usability.</p>
                            <div class="d-flex justify-content-center gap-2">
                                <a href="#" class="btn btn-sm" style="border: 2px solid var(--secondary); border-radius: 50%; width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; box-shadow: 2px 2px 0 var(--shadow-color);">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="#" class="btn btn-sm" style="border: 2px solid var(--secondary); border-radius: 50%; width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; box-shadow: 2px 2px 0 var(--shadow-color);">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                                <a href="#" class="btn btn-sm" style="border: 2px solid var(--secondary); border-radius: 50%; width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; box-shadow: 2px 2px 0 var(--shadow-color);">
                                    <i class="fab fa-dribbble"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- 5th Team Member -->
                <div class="col-lg-3 col-md-6">
                    <div class="neo-card h-100">
                        <div style="height: 8px; background: linear-gradient(90deg, #f7971e, #ffd200);"></div>
                        <div class="card-body p-4 text-center">
                            <div class="mb-3 mx-auto overflow-hidden" style="width: 120px; height: 120px; border-radius: 50%; border: 3px solid var(--secondary); box-shadow: 4px 4px 0 var(--shadow-color);">
                                <img src="https://randomuser.me/api/portraits/men/45.jpg" alt="DevOps Engineer" class="img-fluid">
                            </div>
                            <h5 class="fw-bold mb-1">Michael Lee</h5>
                            <p class="text-muted mb-3">DevOps Engineer</p>
                            <p class="small mb-3">Automation enthusiast ensuring smooth deployments and infrastructure reliability.</p>
                            <div class="d-flex justify-content-center gap-2">
                                <a href="#" class="btn btn-sm" style="border: 2px solid var(--secondary); border-radius: 50%; width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; box-shadow: 2px 2px 0 var(--shadow-color);">
                                    <i class="fab fa-twitter"></i>
                                </a>
                                <a href="#" class="btn btn-sm" style="border: 2px solid var(--secondary); border-radius: 50%; width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; box-shadow: 2px 2px 0 var(--shadow-color);">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- 6th Team Member -->
                <div class="col-lg-3 col-md-6">
                    <div class="neo-card h-100">
                        <div style="height: 8px; background: linear-gradient(90deg, #43cea2, #185a9d);"></div>
                        <div class="card-body p-4 text-center">
                            <div class="mb-3 mx-auto overflow-hidden" style="width: 120px; height: 120px; border-radius: 50%; border: 3px solid var(--secondary); box-shadow: 4px 4px 0 var(--shadow-color);">
                                <img src="https://randomuser.me/api/portraits/women/65.jpg" alt="QA Engineer" class="img-fluid">
                            </div>
                            <h5 class="fw-bold mb-1">Linda Park</h5>
                            <p class="text-muted mb-3">QA Engineer</p>
                            <p class="small mb-3">Quality advocate dedicated to delivering bug-free and reliable software.</p>
                            <div class="d-flex justify-content-center gap-2">
                                <a href="#" class="btn btn-sm" style="border: 2px solid var(--secondary); border-radius: 50%; width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; box-shadow: 2px 2px 0 var(--shadow-color);">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- 7th Team Member -->
                <div class="col-lg-3 col-md-6">
                    <div class="neo-card h-100">
                        <div style="height: 8px; background: linear-gradient(90deg, #fd6e6a, #ffc371);"></div>
                        <div class="card-body p-4 text-center">
                            <div class="mb-3 mx-auto overflow-hidden" style="width: 120px; height: 120px; border-radius: 50%; border: 3px solid var(--secondary); box-shadow: 4px 4px 0 var(--shadow-color);">
                                <img src="https://randomuser.me/api/portraits/men/77.jpg" alt="Product Manager" class="img-fluid">
                            </div>
                            <h5 class="fw-bold mb-1">James Smith</h5>
                            <p class="text-muted mb-3">Product Manager</p>
                            <p class="small mb-3">Bridging business and technology to deliver user-focused solutions.</p>
                            <div class="d-flex justify-content-center gap-2">
                                <a href="#" class="btn btn-sm" style="border: 2px solid var(--secondary); border-radius: 50%; width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center; box-shadow: 2px 2px 0 var(--shadow-color);">
                                    <i class="fab fa-linkedin-in"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Our Values -->
    <div class="mb-5">
        <div class="text-center mb-4">
            <h2 class="display-5 fw-bold mb-3">Our Values</h2>
            <p class="lead col-lg-8 mx-auto">The principles that guide everything we do</p>
        </div>

        <div class="row g-4">
            <div class="col-lg-4">
                <div class="neo-card h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="me-3" style="width: 50px; height: 50px; background: #FFD166; border: 2px solid var(--secondary); border-radius: 8px; display: flex; align-items: center; justify-content: center; box-shadow: 3px 3px 0 var(--shadow-color);">
                                <i class="fas fa-lock fa-lg"></i>
                            </div>
                            <h4 class="fw-bold mb-0">Security First</h4>
                        </div>
                        <p>We prioritize the security of your data above all else. Our infrastructure is built with industry-leading security practices and undergoes regular audits to ensure your information is always protected.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="neo-card h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="me-3" style="width: 50px; height: 50px; background: #06D6A0; border: 2px solid var(--secondary); border-radius: 8px; display: flex; align-items: center; justify-content: center; box-shadow: 3px 3px 0 var(--shadow-color);">
                                <i class="fas fa-dollar-sign fa-lg"></i>
                            </div>
                            <h4 class="fw-bold mb-0">Fair Pricing</h4>
                        </div>
                        <p>We believe in transparent, straightforward pricing. Our token system ensures you only pay for what you use, with no hidden fees or confusing billing structures. We're committed to providing exceptional value.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="neo-card h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="me-3" style="width: 50px; height: 50px; background: #EF476F; border: 2px solid var(--secondary); border-radius: 8px; display: flex; align-items: center; justify-content: center; box-shadow: 3px 3px 0 var(--shadow-color);">
                                <i class="fas fa-users fa-lg"></i>
                            </div>
                            <h4 class="fw-bold mb-0">User-Centered</h4>
                        </div>
                        <p>Every feature we build starts with user needs. We actively listen to feedback and continuously improve our platform to provide the best possible experience for everyone who uses our services.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Our Technology -->
    <div class="mb-5">
        <div class="text-center mb-4">
            <h2 class="display-5 fw-bold mb-3">Our Technology</h2>
            <p class="lead col-lg-8 mx-auto">Powered by industry-leading infrastructure and innovation</p>
        </div>

        <div class="neo-card">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-lg-6 mb-4 mb-lg-0">
                        <h3 class="fw-bold mb-4">State-of-the-Art Cloud Infrastructure</h3>
                        <p>Our platform is built on a distributed cloud architecture that guarantees high availability, performance, and security for all your data processing needs.</p>

                        <div class="mb-4">
                            <h5 class="fw-bold"><i class="fas fa-server me-2 text-primary"></i> Global Data Centers</h5>
                            <p class="mb-0">Strategically located worldwide to ensure low latency no matter where you are.</p>
                        </div>

                        <div class="mb-4">
                            <h5 class="fw-bold"><i class="fas fa-shield-alt me-2 text-primary"></i> Enterprise-Grade Security</h5>
                            <p class="mb-0">Data encryption at rest and in transit, with regular security audits and compliance certifications.</p>
                        </div>

                        <div>
                            <h5 class="fw-bold"><i class="fas fa-tachometer-alt me-2 text-primary"></i> Optimized Performance</h5>
                            <p class="mb-0">Proprietary algorithms that maximize processing efficiency while minimizing token consumption.</p>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="neo-card p-3" style="background-color: #f8f9fa;">
                            <div class="text-center mb-3">
                                <span class="badge" style="background-color: #8338ec; border: 2px solid var(--secondary); box-shadow: 2px 2px 0 var(--shadow-color); padding: 0.5rem 1rem; font-size: 0.875rem;">
                                    Technology Stack
                                </span>
                            </div>
                            <div class="row g-3">
                                <div class="col-4">
                                    <div class="d-flex flex-column align-items-center p-3" style="border: 2px solid var(--secondary); border-radius: 8px; box-shadow: 3px 3px 0 var(--shadow-color);">
                                        <i class="fab fa-aws fa-2x mb-2"></i>
                                        <span class="text-center fw-bold">AWS</span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="d-flex flex-column align-items-center p-3" style="border: 2px solid var(--secondary); border-radius: 8px; box-shadow: 3px 3px 0 var(--shadow-color);">
                                        <i class="fab fa-docker fa-2x mb-2"></i>
                                        <span class="text-center fw-bold">Docker</span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="d-flex flex-column align-items-center p-3" style="border: 2px solid var(--secondary); border-radius: 8px; box-shadow: 3px 3px 0 var(--shadow-color);">
                                        <i class="fab fa-kubernetes fa-2x mb-2"></i>
                                        <span class="text-center fw-bold">Kubernetes</span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="d-flex flex-column align-items-center p-3" style="border: 2px solid var(--secondary); border-radius: 8px; box-shadow: 3px 3px 0 var(--shadow-color);">
                                        <i class="fab fa-laravel fa-2x mb-2"></i>
                                        <span class="text-center fw-bold">Laravel</span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="d-flex flex-column align-items-center p-3" style="border: 2px solid var(--secondary); border-radius: 8px; box-shadow: 3px 3px 0 var(--shadow-color);">
                                        <i class="fab fa-vuejs fa-2x mb-2"></i>
                                        <span class="text-center fw-bold">Vue.js</span>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="d-flex flex-column align-items-center p-3" style="border: 2px solid var(--secondary); border-radius: 8px; box-shadow: 3px 3px 0 var(--shadow-color);">
                                        <i class="fas fa-database fa-2x mb-2"></i>
                                        <span class="text-center fw-bold">MySQL</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- By the Numbers -->
    <div class="mb-5">
        <div class="text-center mb-4">
            <h2 class="display-5 fw-bold mb-3">By the Numbers</h2>
            <p class="lead col-lg-8 mx-auto">Our impact and growth since launch</p>
        </div>

        <div class="row g-4">
            <div class="col-md-3">
                <div class="neo-card h-100 text-center p-4">
                    <div class="mb-3" style="width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, #ff9a9e, #fad0c4); border: 3px solid var(--secondary); margin: 0 auto; display: flex; align-items: center; justify-content: center; box-shadow: 3px 3px 0 var(--shadow-color);">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                    <h2 class="display-5 fw-bold mb-0">10K+</h2>
                    <p class="mb-0">Active Users</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="neo-card h-100 text-center p-4">
                    <div class="mb-3" style="width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, #a1c4fd, #c2e9fb); border: 3px solid var(--secondary); margin: 0 auto; display: flex; align-items: center; justify-content: center; box-shadow: 3px 3px 0 var(--shadow-color);">
                        <i class="fas fa-download fa-2x"></i>
                    </div>
                    <h2 class="display-5 fw-bold mb-0">5M+</h2>
                    <p class="mb-0">Downloads Processed</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="neo-card h-100 text-center p-4">
                    <div class="mb-3" style="width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, #fccb90, #d57eeb); border: 3px solid var(--secondary); margin: 0 auto; display: flex; align-items: center; justify-content: center; box-shadow: 3px 3px 0 var(--shadow-color);">
                        <i class="fas fa-coins fa-2x"></i>
                    </div>
                    <h2 class="display-5 fw-bold mb-0">50M+</h2>
                    <p class="mb-0">Tokens Used</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="neo-card h-100 text-center p-4">
                    <div class="mb-3" style="width: 80px; height: 80px; border-radius: 50%; background: linear-gradient(135deg, #84fab0, #8fd3f4); border: 3px solid var(--secondary); margin: 0 auto; display: flex; align-items: center; justify-content: center; box-shadow: 3px 3px 0 var(--shadow-color);">
                        <i class="fas fa-server fa-2x"></i>
                    </div>
                    <h2 class="display-5 fw-bold mb-0">99.9%</h2>
                    <p class="mb-0">Uptime</p>
                </div>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="neo-card p-5 mb-5 text-center">
        <h2 class="display-5 fw-bold mb-3">Ready to Get Started?</h2>
        <p class="lead col-lg-8 mx-auto mb-4">Join thousands of users who trust our token-based cloud computing platform.</p>
        <div class="d-flex justify-content-center gap-3">
            @guest
                <a href="{{ route('register') }}" class="neo-btn btn-lg">
                    <i class="fas fa-user-plus me-2"></i> Create Account
                </a>
                {{-- <a href="{{ route('contact') }}" class="neo-btn btn-secondary btn-lg">
                    <i class="fas fa-envelope me-2"></i> Contact Us
                </a> --}}
            @else
                <a href="{{ route('dashboard') }}" class="neo-btn btn-lg">
                    <i class="fas fa-tachometer-alt me-2"></i> Go to Dashboard
                </a>
                <a href="{{ route('contact') }}" class="neo-btn btn-secondary btn-lg">
                    <i class="fas fa-envelope me-2"></i> Contact Us
                </a>
            @endguest
        </div>
    </div>
</div>
@endsection

@push('styles')
<!-- <style>
    :root {
        --primary: #ff6b6b;
        --secondary: #212529;
        --shadow-color: rgba(0, 0, 0, 0.2);
    }

    .text-primary-gradient {
        background: linear-gradient(90deg, #ff6b6b, #ff8e53);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .neo-card {
        border: 3px solid var(--secondary);
        border-radius: 8px;
        box-shadow: 5px 5px 0 var(--shadow-color);
        background: #ffffff;
        overflow: hidden;
    }

    .neo-card .card-header {
        background: linear-gradient(90deg, #ff9a9e 0%, #fad0c4 100%);
        border-bottom: 2px solid var(--secondary);
        padding: 1rem;
    }

    .neo-btn {
        display: inline-block;
        font-weight: 600;
        text-align: center;
        vertical-align: middle;
        user-select: none;
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        line-height: 1.5;
        border: 2px solid var(--secondary);
        border-radius: 0.375rem;
        background: linear-gradient(90deg, #ff9a9e 0%, #fad0c4 100%);
        box-shadow: 3px 3px 0 var(--shadow-color);
        transition: transform 0.1s, box-shadow 0.1s;
        cursor: pointer;
        text-decoration: none;
        color: var(--secondary);
    }

    .neo-btn:hover {
        transform: translate(-1px, -1px);
        box-shadow: 4px 4px 0 var(--shadow-color);
        text-decoration: none;
    }

    .neo-btn:active {
        transform: translate(1px, 1px);
        box-shadow: 2px 2px 0 var(--shadow-color);
    }

    .neo-btn.btn-secondary {
        background: #f8f9fa;
    }

    .neo-btn.btn-lg {
        padding: 0.5rem 1rem;
        font-size: 1.25rem;
    }

    .ratio-1x1 {
        position: relative;
        width: 100%;
        padding-top: 100%;
    }

    .ratio-1x1 > div {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
    }
</style> -->
@endpush
