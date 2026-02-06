<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = \Faker\Factory::create();

        $homeHTML = '
        <div class="container topmargin-lg bottommargin-lg">
            <div class="heading-block mw-xs mx-auto text-center mb-6">
                <h3 class=" nott ls0">Tour Packages</h3>
            </div>

            <div class="row">
                <div class="col-lg-4 px-lg-5 mt-5 mt-lg-0 text-center">
                    <div class="mb-4">
                        <img src="'.\URL::to('/').'/theme/images/travels/image1.jpg" />
                    </div>
                    <h3 class="fw-semibold font-body mb-4 ">The most comprehensive template collection on envato.</h3>
                    <p class="op-06 fw-medium ">"Completely productivate quality web services rather than standards compliant niches. Continually engineer."</p>
                </div>
                <div class="col-lg-4 px-lg-5 mt-5 mt-lg-0 text-center">
                    <div class="mb-4">
                        <img src="'.\URL::to('/').'/theme/images/travels/image2.jpg" />
                    </div>
                    <h3 class="fw-semibold font-body mb-4 ">Awesome Design &amp; Customer Support.</h3>
                    <p class="op-06 fw-medium ">"Amazing WORK ! This guys also very fast for support. No matter Sunday or Monday. I get my answers and they were really patiently with my sometimes stupid questions!"</p>
                </div>
                <div class="col-lg-4 px-lg-5 mt-5 mt-lg-0 text-center">
                    <div class="mb-4">
                        <img src="'.\URL::to('/').'/theme/images/travels/image3.jpg" />
                    </div>
                    <h3 class="fw-semibold font-body mb-4 ">Flexibility and Feature Availability</h3>
                    <p class="op-06 fw-medium ">"A great thing that there are many demos available otherwise all of the great implementation and features would never be used or understood the right way."</p>
                </div>
            </div>

            <div class="text-center m-auto w-75">					
                <a href="tour-package-details.htm" class="button button-border button-rounded ms-0 topmargin-sm button-small">View All</a>
            </div>
        </div>
        
        <div class="section mb-0 mt-0" style="background-color:#264653;">
            <div class="container">
                <div class="heading-block mw-xs mx-auto text-center mb-6">
                    <h3 class="nott ls0 text-white">Latest News</h3>
                </div>

                <div id="oc-posts" class="owl-carousel posts-carousel carousel-widget posts-md" data-pagi="false" data-items-xs="1" data-items-sm="2" data-items-md="3" data-items-lg="4">
                    <div class="oc-item">
                        <div class="entry topmargin-sm">
                            <div class="entry-image">
                                <a href="#"><img src="'.\URL::to('/').'/theme/images/news/news1.jpg" alt="Image"></a>
                            </div>
                            <div class="entry-title title-xs nott">
                                <h3><a href="#" class="text-white">Bloomberg smart cities; change-makers economic security</a></h3>
                            </div>
                            <div class="entry-meta">
                                <ul>
                                    <li><i class="icon-calendar3"></i> 13th Jun 2021</li>
                                </ul>
                            </div>
                            <div class="entry-content mt-3 text-white">
                                <p>Prevention effect, advocate dialogue rural development lifting people up community civil society. Catalyst, grantees leverage.</p>
                            </div>
                        </div>
                    </div>

                    <div class="oc-item">
                        <div class="entry topmargin-sm">
                            <div class="entry-image">
                                <a href="#"><img src="'.\URL::to('/').'/theme/images/news/news2.jpg" alt="Image"></a>
                            </div>
                            <div class="entry-title title-xs nott">
                                <h3><a href="#" class="text-white">Medicine new approaches communities, outcomes partnership</a></h3>
                            </div>
                            <div class="entry-meta">
                                <ul>
                                    <li><i class="icon-calendar3"></i> 24th Feb 2021</li>
                                </ul>
                            </div>
                            <div class="entry-content mt-3 text-white">
                                <p>Cross-agency coordination clean water rural, promising development turmoil inclusive education transformative community.</p>
                            </div>
                        </div>
                    </div>

                    <div class="oc-item">
                        <div class="entry topmargin-sm">
                            <div class="entry-image">
                                <a href="#"><img src="'.\URL::to('/').'/theme/images/news/news3.jpg" alt="Image"></a>
                            </div>
                            <div class="entry-title title-xs nott">
                                <h3><a href="#" class="text-white">Significant altruism planned giving insurmountable challenges liberal</a></h3>
                            </div>
                            <div class="entry-meta">
                                <ul>
                                    <li><i class="icon-calendar3"></i> 30th Dec 2021</li>
                                </ul>
                            </div>
                            <div class="entry-content mt-3 text-white">
                                <p>Micro-finance; vaccines peaceful contribution citizens of change generosity. Measures design thinking accelerate progress medical initiative.</p>
                            </div>
                        </div>
                    </div>

                    <div class="oc-item">
                        <div class="entry topmargin-sm">
                            <div class="entry-image">
                                <a href="#"><img src="'.\URL::to('/').'/theme/images/news/news4.jpg" alt="Image"></a>
                            </div>
                            <div class="entry-title title-xs nott">
                                <h3><a href="#" class="text-white">Compassion conflict resolution, progressive; tackle</a></h3>
                            </div>
                            <div class="entry-meta">
                                <ul>
                                    <li><i class="icon-calendar3"></i> 15th Jan 2021</li>
                                </ul>
                            </div>
                            <div class="entry-content mt-3 text-white">
                                <p>Community health workers best practices, effectiveness meaningful work The Elders fairness. Our ambitions local solutions globalization.</p>
                            </div>
                        </div>
                    </div>

                    <div class="oc-item">
                        <div class="entry topmargin-sm">
                            <div class="entry-image">
                                <a href="#"><img src="'.\URL::to('/').'/theme/images/news/news2.jpg" alt="Image"></a>
                            </div>
                            <div class="entry-title title-xs nott">
                                <h3><a href="#" class="text-white">Medicine new approaches communities, outcomes partnership</a></h3>
                            </div>
                            <div class="entry-meta">
                                <ul>
                                    <li><i class="icon-calendar3"></i> 24th Feb 2021</li>
                                </ul>
                            </div>
                            <div class="entry-content mt-3 text-white">
                                <p>Cross-agency coordination clean water rural, promising development turmoil inclusive education transformative community.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-center m-auto w-75">					
                    <a href="news.htm" class="button button-border button-rounded ms-0 topmargin-sm button-small button-yellow">Read More</a>
                </div>
            </div>
        </div>
        
        
        <div class="container topmargin-lg bottommargin-lg">
            <div class="row">
                <div class="col-md-4 text-center">
                    <span class="icon-image1 icon-5x"></span>
                    <h3>Item It</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cupiditate, asperiores quod est tenetur in. Eligendi, deserunt, blanditiis est quisquam doloribus voluptate id aperiam ea ipsum magni aut perspiciatis rem voluptatibus.</p>
                </div>
                <div class="col-md-4 text-center">
                    <span class="icon-image1 icon-5x"></span>
                    <h3>Live It</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cupiditate, asperiores quod est tenetur in. Eligendi, deserunt, blanditiis est quisquam doloribus voluptate id aperiam ea ipsum magni aut perspiciatis rem voluptatibus.</p>
                </div>
                <div class="col-md-4 text-center">
                    <span class="icon-image1 icon-5x"></span>
                    <h3>Love It</h3>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cupiditate, asperiores quod est tenetur in. Eligendi, deserunt, blanditiis est quisquam doloribus voluptate id aperiam ea ipsum magni aut perspiciatis rem voluptatibus.</p>
                </div>
            </div>
        </div>
        
        <div class="section m-0 p-0">
            <div class="container topmargin-lg bottommargin-lg">
                <div class="col-12">
                    <div class="heading-block mw-xs mx-auto text-center mb-2">
                        <h3 class=" nott ls0">Affiliated With</h3>
                    </div>

                    <div id="oc-clients" class="owl-carousel image-carousel carousel-widget" data-margin="60" data-loop="true" data-nav="false" data-autoplay="5000" data-pagi="false" data-items-xs="2" data-items-sm="3" data-items-md="4" data-items-lg="5" data-items-xl="5">

                        <div class="oc-item"><a href="#"><img src="'.\URL::to('/').'/theme/images/clients/dot.png" alt="Clients"></a></div>
                        <div class="oc-item"><a href="#"><img src="'.\URL::to('/').'/theme/images/clients/iata.png" alt="Clients"></a></div>
                        <div class="oc-item"><a href="#"><img src="'.\URL::to('/').'/theme/images/clients/philtoa.png" alt="Clients"></a></div>
                        <div class="oc-item"><a href="#"><img src="'.\URL::to('/').'/theme/images/clients/piata.png" alt="Clients"></a></div>
                        <div class="oc-item"><a href="#"><img src="'.\URL::to('/').'/theme/images/clients/ptaa.png" alt="Clients"></a></div>
                        <div class="oc-item"><a href="#"><img src="'.\URL::to('/').'/theme/images/clients/tcp.png" alt="Clients"></a></div>
                        <div class="oc-item"><a href="#"><img src="'.\URL::to('/').'/theme/images/clients/women.png" alt="Clients"></a></div>
                        <div class="oc-item"><a href="#"><img src="'.\URL::to('/').'/theme/images/clients/immigration.png" alt="Clients"></a></div>

                    </div>
                </div>
            </div>
        </div>';


        $aboutHTML = '
            <div class="container topmargin-lg bottommargin-lg">
                <div class="row">
                    <span onclick="closeNav()" class="dark-curtain"></span>
                    <div class="col-lg-12 col-md-5 col-sm-12">
                        <span onclick="openNav()" class="button button-small button-circle border-bottom ms-0 text-initial nols fw-normal noleftmargin d-lg-none mb-4"><span class="icon-chevron-left me-2 color-2"></span> Quicklinks</span>
                    </div>
                    <div class="col-lg-3 pe-lg-4">
                        <div class="tablet-view">
                            <a href="javascript:void(0)" class="closebtn d-block d-lg-none" onclick="closeNav()">&times;</a>

                            <div class="card border-0">
                                <h3>Quicklinks</h3>
                                <div class="side-menu">
                                    <ul class="mb-0 pb-0">
                                        <li class="active"><a href="#"><div>Company Profile</div></a></li>
                                        <li><a href="#"><div>Awards</div></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <h2>Who We Are</h2>

                        <div class="row">
                            <div class="col-md-5">
                                <img src="'.\URL::to('/').'/theme/images/travels/image3.jpg" alt="We\'re divided land his creature which have evening subdue">
                            </div>
                            <div class="col-md-7">
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>

                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>';


        $servicesHTML = '
            <div class="container topmargin-lg bottommargin-lg">
                <div class="row">
                    <span onclick="closeNav()" class="dark-curtain"></span>
                    <div class="col-lg-12 col-md-5 col-sm-12">
                        <span onclick="openNav()" class="button button-small button-circle border-bottom ms-0 text-initial nols fw-normal noleftmargin d-lg-none mb-4"><span class="icon-chevron-left me-2 color-2"></span> Quicklinks</span>
                    </div>
                    <div class="col-lg-3 pe-lg-4">
                        <div class="tablet-view">
                            <a href="javascript:void(0)" class="closebtn d-block d-lg-none" onclick="closeNav()">&times;</a>

                            <div class="card border-0">
                                <h3>Quicklinks</h3>
                                <div class="side-menu">
                                    <ul class="mb-0 pb-0">
                                        <li class="active"><a class="menu-link" href="services.htm"><div>Ticketing and Reservations</div></a></li>
                                        <li><a class="menu-link" href="services.htm"><div>Hotel Itemings and Reservations</div></a></li>
                                        <li><a class="menu-link" href="services.htm"><div>Passport and Visa Processing</div></a></li>
                                        <li><a class="menu-link" href="services.htm"><div>Travel Insurance</div></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <h2>Ticketing and Reservations</h2>

                        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."</p>

                        <p class="nobottommargin">"Sed ut perspiciatis unde omnis iste natus error sit voluptatem accusantium doloremque laudantium, totam rem aperiam, eaque ipsa quae ab illo inventore veritatis et quasi architecto beatae vitae dicta sunt explicabo. Nemo enim ipsam voluptatem quia voluptas sit aspernatur aut odit aut fugit, sed quia consequuntur magni dolores eos qui ratione voluptatem sequi nesciunt. Neque porro quisquam est, qui dolorem ipsum quia dolor sit amet, consectetur, adipisci velit, sed quia non numquam eius modi tempora incidunt ut labore et dolore magnam aliquam quaerat voluptatem. Ut enim ad minima veniam, quis nostrum exercitationem ullam corporis suscipit laboriosam, nisi ut aliquid ex ea commodi consequatur? Quis autem vel eum iure reprehenderit qui in ea voluptate velit esse quam nihil molestiae consequatur, vel illum qui dolorem eum fugiat quo voluptas nulla pariatur?</p>
                    </div>
                </div>
            </div>
        ';

        $tourpackagesHTML = '
            <div class="container topmargin-lg bottommargin-lg">
                <div class="row">
                    <div class="entry col-md-4 px-lg-5 mt-5 mt-lg-0">
                        <div class="grid-inner row g-0">
                            <div class="col-12">
                                <div class="news-imag">
                                    <a href="tour-package-details.htm"><img src="'.\URL::to('/').'/theme/images/travels/image2.jpg" alt="We\'re divided land his creature which have evening subdue"></a>
                                </div>
                            </div>
                            <div class="col-12 text-center">
                                <div class="entry-title title-sm py-3">
                                    <h2><a href="tour-package-details.htm">This is a Standard post with a Preview Image</a></h2>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="entry col-md-4 px-lg-5 mt-5 mt-lg-0">
                        <div class="grid-inner row g-0">
                            <div class="col-12">
                                <div class="news-imag">
                                    <a href="tour-package-details.htm"><img src="'.\URL::to('/').'/theme/images/travels/image1.jpg" alt="We\'re divided land his creature which have evening subdue"></a>
                                </div>
                            </div>
                            <div class="col-12 text-center">
                                <div class="entry-title title-sm py-3">
                                    <h2><a href="tour-package-details.htm">This is a Standard post with a Preview Image</a></h2>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="entry col-md-4 px-lg-5 mt-5 mt-lg-0">
                        <div class="grid-inner row g-0">
                            <div class="col-12">
                                <div class="news-imag">
                                    <a href="tour-package-details.htm"><img src="'.\URL::to('/').'/theme/images/travels/image3.jpg" alt="We\'re divided land his creature which have evening subdue"></a>
                                </div>
                            </div>
                            <div class="col-12 text-center">
                                <div class="entry-title title-sm py-3">
                                    <h2><a href="tour-package-details.htm">This is a Standard post with a Preview Image</a></h2>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="entry col-md-4 px-lg-5 mt-5 mt-lg-0">
                        <div class="grid-inner row g-0">
                            <div class="col-12">
                                <div class="news-imag">
                                    <a href="tour-package-details.htm"><img src="'.\URL::to('/').'/theme/images/travels/image2.jpg" alt="We\'re divided land his creature which have evening subdue"></a>
                                </div>
                            </div>
                            <div class="col-12 text-center">
                                <div class="entry-title title-sm py-3">
                                    <h2><a href="tour-package-details.htm">This is a Standard post with a Preview Image</a></h2>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        ';

        $contactUsHTML = '
            <div class="col-12">
                <h3>Contact Details</h3>
            </div>
            <div class="col-lg-7 mb-5">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3862.4304358272857!2d120.99440521464825!3d14.517354383003676!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397ceafabef58cb%3A0x7a5d4f5233f8f45b!2sHouse%20Of%20Travel!5e0!3m2!1sen!2sph!4v1679467638444!5m2!1sen!2sph" width="100%" height="70" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>

                <div class="row topmargin d-none">
                    <div class="col-lg-6">
                        <address>
                            <abbr title="Address">Address:</abbr><br>
                            444a EDSA, Guadalupe Viejo, Makati City, Philippines 1211
                        </address>
                    </div>
                    <div class="col-lg-6">
                        <p><abbr title="Email Address">Email:</abbr><br>info@vanguard.edu.ph</p>
                    </div>
                    <div class="col-lg-6">
                        <p class="nomargin"><abbr title="Phone Number">Phone:</abbr><br>(632) 8-1234-4567</p>
                    </div>
                    <div class="col-lg-6">
                        <p class="nomargin"><abbr title="Phone Number">Fax:</abbr><br>(632) 8-1234-4567</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="table-responsive-faker">
                    <table>
                        <tbody>
                        <tr>
                            <td><i class="bg-transparent i-small icon-line-map-pin m-0 me-1"></i></td>
                            <td><h5 class="m-0">2/F Anflocor Building 411 Quirino Aveñue, corner NAIA Road, Barangay Tambo Parañaque City, Metro Manila</h5></td>
                        </tr>
                        <tr><td colspan="2">&nbsp;</td></tr>
                        <tr>
                            <td><i class="bg-transparent i-small icon-phone1 m-0 me-1"></i></td>
                            <td><h6 class="m-0">(+63) (2) 8832-2404 <br>(+63) (2) 8853-3988 <br>(+63) (2) 8855-2741 to 47</h6></td>
                        </tr>
                        <tr><td colspan="2">&nbsp;</td></tr>
                        <tr>
                            <td><i class="bg-transparent i-small icon-clock2 m-0 me-1"></i></td>
                            <td><h6 class="m-0">Monday – Thursday: 8:00AM – 6:00PM
                            <br>Friday: 8:00AM – 5:00PM</h6></td>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <a href="#" class="social-icon si-small si-light si-facebook">
                    <i class="icon-facebook"></i>
                    <i class="icon-facebook"></i>
                </a>

                <br><br>
            </div>';

        $footerHTML = '
        <footer id="footer" class="border-0 border-top">
            <div class="container">

                <!-- Footer Widgets
                ============================================= -->
                <div class="footer-widgets-wrap">

                    <div class="row justify-content-between">
                        <div class="col-lg-10 offset-lg-1 mb-5 mb-lg-0">
                            <div class="fw-semibold font-primary color ls3 h2 text-uppercase mb-0"><img src="'.\URL::to('/').'/theme/images/hoti-logo-white.png" /></div>
                            
                            <div class="row">
                                <div class="col-lg-5 dark">
                                    <div class="table-responsive-faker">
                                        <table>
                                            <tbody>
                                            <tr>
                                                <td><i class="bg-transparent i-small icon-line-map-pin m-0 me-1"></i></td>
                                                <td><h5 class="m-0">2/F Anflocor Building 411 Quirino Aveñue, corner NAIA Road, Barangay Tambo Parañaque City, Metro Manila</h5></td>
                                            </tr>
                                            <tr><td colspan="2">&nbsp;</td></tr>
                                            <tr>
                                                <td><i class="bg-transparent i-small icon-phone1 m-0 me-1"></i></td>
                                                <td><h6 class="m-0">(+63) (2) 8832-2404 <br>(+63) (2) 8853-3988 <br>(+63) (2) 8855-2741 to 47</h6></td>
                                            </tr>
                                            <tr><td colspan="2">&nbsp;</td></tr>
                                            <tr>
                                                <td><i class="bg-transparent i-small icon-clock2 m-0 me-1"></i></td>
                                                <td><h6 class="m-0">Monday – Thursday: 8:00AM – 6:00PM
                                                <br>Friday: 8:00AM – 5:00PM</h6></td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    
                                    
                                    <a href="#" class="social-icon si-small si-light si-facebook">
                                        <i class="icon-facebook"></i>
                                        <i class="icon-facebook"></i>
                                    </a>
                                    
                                    <br><br>
                                </div>
                                <div class="col-lg-7 dark">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-2">
                                                <h5 class="m-0">Ticketing Department</h5>
                                                <small>For ticketing and reservation inquiry:</small>
                                                <table class="table-responsive m-0">
                                                    <tbody>
                                                        <tr>
                                                            <td><i class="bg-transparent i-small icon-envelope21 m-0 me-1"></i></td>
                                                            <td><a class="text-white text-decoration-underline" href="mailto:ticketing@houseoftravel.com.ph">ticketing@houseoftravel.com.ph</a></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                
                                            </div>
                                            
                                            <div class="mb-2">
                                                <h5 class="m-0">Tours Department</h5>
                                                <small>For tour packages inquiry:</small>
                                                <table class="table-responsive m-0">
                                                    <tbody>
                                                        <tr>
                                                            <td><i class="bg-transparent i-small icon-envelope21 m-0 me-1"></i></td>
                                                            <td><a class="text-white text-decoration-underline"  href="mailto:tours@houseoftravel.com.ph">tours@houseoftravel.com.ph</a></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            
                                            <div class="mb-2">
                                                <h5 class="m-0">Documentation Department</h5>
                                                <small>For passport, visa and other document concern:</small>
                                                <table class="table-responsive m-0">
                                                    <tbody>
                                                        <tr>
                                                            <td><i class="bg-transparent i-small icon-envelope21 m-0 me-1"></i></td>
                                                            <td><a class="text-white text-decoration-underline"  href="mailto:documentation@houseoftravel.com.ph">documentation@houseoftravel.com.ph</a></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-2">
                                                <h5 class="m-0">Pearl Farm Manila Department</h5>
                                                <small>For pearl farm reservation inquiry:</small>
                                                <table class="table-responsive m-0">
                                                    <tbody>
                                                        <tr>
                                                            <td><i class="bg-transparent i-small icon-envelope21 m-0 me-1"></i></td>
                                                            <td><a class="text-white text-decoration-underline"  href="mailto:pearlfarm@houseoftravel.com.ph">pearlfarm@houseoftravel.com.ph</a></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                            
                                            <div class="mb-2">
                                                <h5 class="m-0">Technical Support Department</h5>
                                                <small>For website technical issues concern:</small>
                                                <table class="table-responsive m-0">
                                                    <tbody>
                                                        <tr>
                                                            <td><i class="bg-transparent i-small icon-envelope21 m-0 me-1"></i></td>
                                                            <td><a class="text-white text-decoration-underline" href="mailto:support@houseoftravel.com.ph">support@houseoftravel.com.ph</a></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div><!-- .footer-widgets-wrap end -->

            </div>

            <!-- Copyrights
            ============================================= -->
            <div id="copyrights" class="">
                <div class="container">

                    <div class="row justify-content-between">

                        <div class="col">
                            <span class="text-black-50">&copy; 2023 House of Travel, Inc.</span>
                        </div>

                        <div class="col text-end">
                            <a href="#">Home</a>/<a href="#">About</a>/<a href="#">Service</a>/<a href="#">Tour Packages</a>/<a href="#">Contact</a>
                        </div>

                    </div>

                </div>
            </div><!-- #copyrights end -->
        </footer><!-- #footer end -->';

      
        $pages = [
            [
                'parent_page_id' => 0,
                'album_id' => 1,
                'slug' => 'home',
                'name' => 'Home',
                'label' => 'Home',
                'contents' => $homeHTML,
                'status' => 'PUBLISHED',
                'page_type' => 'default',
                'image_url' => '',
                'meta_title' => 'Home',
                'meta_keyword' => 'home',
                'meta_description' => 'Home page',
                'user_id' => 1,
                'template' => 'home',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],
            [
                'parent_page_id' => 0,
                'album_id' => 2,
                'slug' => 'about-us',
                'name' => 'About',
                'label' => 'About',
                'contents' => $aboutHTML,
                'status' => 'PUBLISHED',
                'page_type' => 'standard',
                'image_url' => '',
                'meta_title' => 'About Us',
                'meta_keyword' => 'About Us',
                'meta_description' => 'About Us page',
                'user_id' => 1,
                'template' => '',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],
            [
                'parent_page_id' => 0,
                'album_id' => 2,
                'slug' => 'services',
                'name' => 'Services',
                'label' => 'Services',
                'contents' => $servicesHTML,
                'status' => 'PUBLISHED',
                'page_type' => 'standard',
                'image_url' => '',
                'meta_title' => 'Services',
                'meta_keyword' => 'Services',
                'meta_description' => 'Services page',
                'user_id' => 1,
                'template' => '',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],
            [
                'parent_page_id' => 0,
                'album_id' => 2,
                'slug' => 'tour-packages',
                'name' => 'Tour Packages',
                'label' => 'Tour Packages',
                'contents' => $tourpackagesHTML,
                'status' => 'PUBLISHED',
                'page_type' => 'standard',
                'image_url' => '',
                'meta_title' => 'Tour Packages',
                'meta_keyword' => 'Tour Packages',
                'meta_description' => 'Tour Packages page',
                'user_id' => 1,
                'template' => '',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],

            [
                'parent_page_id' => 0,
                'album_id' => 2,
                'slug' => 'contact-us',
                'name' => 'Contact Us',
                'label' => 'Contact Us',
                'contents' => $contactUsHTML,
                'status' => 'PUBLISHED',
                'page_type' => 'standard',
                'image_url' => '',
                'meta_title' => 'Contact Us',
                'meta_keyword' => 'Contact Us',
                'meta_description' => 'Contact Us page',
                'user_id' => 1,
                'template' => 'contact-us',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],
            /*[
                'parent_page_id' => 0,
                'album_id' => 0,
                'slug' => 'news',
                'name' => 'News and Updates',
                'label' => 'News and Updates',
                'contents' => '',
                'status' => 'PUBLISHED',
                'page_type' => 'customize',
                'image_url' => '',
                'meta_title' => 'News',
                'meta_keyword' => 'news',
                'meta_description' => 'News page',
                'user_id' => 1,
                'template' => 'news',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],*/
            [
                'parent_page_id' => 0,
                'album_id' => 0,
                'slug' => 'footer',
                'name' => 'Footer',
                'label' => 'footer',
                'contents' => $footerHTML,
                'status' => 'PUBLISHED',
                'page_type' => 'default',
                'image_url' => '',
                'meta_title' => '',
                'meta_keyword' => '',
                'meta_description' => '',
                'user_id' => 1,
                'template' => '',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],

            //About submenus
            [
                'parent_page_id' => 0,
                'album_id' => 0,
                'slug' => 'company-profile',
                'name' => 'Company Profile',
                'label' => 'Company Profile',
                'contents' => '',
                'status' => 'PUBLISHED',
                'page_type' => 'default',
                'image_url' => '',
                'meta_title' => 'Company Profile',
                'meta_keyword' => 'Company Profile',
                'meta_description' => 'Company Profile Page',
                'user_id' => 1,
                'template' => '',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],
            [
                'parent_page_id' => 0,
                'album_id' => 0,
                'slug' => 'awards',
                'name' => 'Awards',
                'label' => 'Awards',
                'contents' => '',
                'status' => 'PUBLISHED',
                'page_type' => 'default',
                'image_url' => '',
                'meta_title' => 'Awards',
                'meta_keyword' => 'Awards',
                'meta_description' => 'Awards Page',
                'user_id' => 1,
                'template' => '',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],

            //Services
            [
                'parent_page_id' => 0,
                'album_id' => 0,
                'slug' => 'ticketing-and-reservations',
                'name' => 'Ticketing and Reservations',
                'label' => 'Ticketing and Reservations',
                'contents' => '',
                'status' => 'PUBLISHED',
                'page_type' => 'default',
                'image_url' => '',
                'meta_title' => 'Ticketing and Reservations',
                'meta_keyword' => 'Ticketing and Reservations',
                'meta_description' => 'Ticketing and Reservations Page',
                'user_id' => 1,
                'template' => '',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],
            [
                'parent_page_id' => 0,
                'album_id' => 0,
                'slug' => 'hotel-bookings-and-reservations',
                'name' => 'Hotel Itemings and Reservations',
                'label' => 'Hotel Itemings and Reservations',
                'contents' => '',
                'status' => 'PUBLISHED',
                'page_type' => 'default',
                'image_url' => '',
                'meta_title' => 'Hotel Itemings and Reservations',
                'meta_keyword' => 'Hotel Itemings and Reservations',
                'meta_description' => 'Hotel Itemings and Reservations Page',
                'user_id' => 1,
                'template' => '',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],
            [
                'parent_page_id' => 0,
                'album_id' => 0,
                'slug' => 'passport-and-visa-processing',
                'name' => 'Passport and Visa Processing',
                'label' => 'Passport and Visa Processing',
                'contents' => '',
                'status' => 'PUBLISHED',
                'page_type' => 'default',
                'image_url' => '',
                'meta_title' => 'Passport and Visa Processing',
                'meta_keyword' => 'Passport and Visa Processing',
                'meta_description' => 'Passport and Visa Processing Page',
                'user_id' => 1,
                'template' => '',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],
            [
                'parent_page_id' => 0,
                'album_id' => 0,
                'slug' => 'travel-insurance',
                'name' => 'Travel Insurance',
                'label' => 'Travel Insurance',
                'contents' => '',
                'status' => 'PUBLISHED',
                'page_type' => 'default',
                'image_url' => '',
                'meta_title' => 'Travel Insurance',
                'meta_keyword' => 'Travel Insurance',
                'meta_description' => 'Travel Insurance Page',
                'user_id' => 1,
                'template' => '',
                'created_at' => date("Y-m-d H:i:s"),
                'updated_at' => date("Y-m-d H:i:s")
            ],
        ];

        DB::table('pages')->insert($pages);
    }
}
