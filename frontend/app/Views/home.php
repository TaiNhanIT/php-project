<div class="container m-auto">
<!-- Banner -->
    <div x-data="carousel({
            // Sets the time between each slides in milliseconds
            intervalTime: 3000,
            slides: [
                {
                    imgSrc: '/assets/images/slider/slider1.jpg',
                    imgAlt: 'Slider Image',
                },
                {
                    imgSrc: '/assets/images/slider/slider2.jpg',
                    imgAlt: 'Slider Image',
                },
                {
                    imgSrc: '/assets/images/slider/slider3.jpg',
                    imgAlt: 'Slider Image',
                },
            ],
        })" x-init="autoplay" class="relative w-full overflow-hidden">

        <!-- slides -->
        <!-- Change min-h-[50svh] to your preferred height size -->
        <div class="relative min-h-[50svh] w-full">
            <template x-for="(slide, index) in slides">
                <div x-cloak x-show="currentSlideIndex == index + 1" class="absolute inset-0" x-transition.opacity.duration.1000ms>
                    <img class="absolute w-full h-full inset-0 object-cover text-on-surface dark:text-on-surface-dark" x-bind:src="slide.imgSrc" x-bind:alt="slide.imgAlt" />
                </div>
            </template>
        </div>

        <!-- indicators -->
        <div class="absolute rounded-radius bottom-3 md:bottom-5 left-1/2 z-20 flex -translate-x-1/2 gap-4 md:gap-3 px-1.5 py-1 md:px-2" role="group" aria-label="slides" >
            <template x-for="(slide, index) in slides">
                <button class="size-2 rounded-full transition" x-on:click="(currentSlideIndex = index + 1), setAutoplayIntervalTime(autoplayIntervalTime)" x-bind:class="[currentSlideIndex === index + 1 ? 'bg-on-surface-dark' : 'bg-on-surface-dark/50']" x-bind:aria-label="'slide ' + (index + 1)"></button>
            </template>
        </div>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('carousel', (carouselData = {
                slides: [],
                intervalTime: 0,
            },) => ({
                slides: carouselData.slides,
                autoplayIntervalTime: carouselData.intervalTime,
                currentSlideIndex: 1,
                isPaused: false,
                autoplayInterval: null,
                previous() {
                    if (this.currentSlideIndex > 1) {
                        this.currentSlideIndex = this.currentSlideIndex - 1
                    } else {
                        // If it's the first slide, go to the last slide
                        this.currentSlideIndex = this.slides.length
                    }
                },
                next() {
                    if (this.currentSlideIndex < this.slides.length) {
                        this.currentSlideIndex = this.currentSlideIndex + 1
                    } else {
                        // If it's the last slide, go to the first slide
                        this.currentSlideIndex = 1
                    }
                },
                autoplay() {
                    this.autoplayInterval = setInterval(() => {
                        if (! this.isPaused) {
                            this.next()
                        }
                    }, this.autoplayIntervalTime)
                },
                // Updates interval time
                setAutoplayIntervalTime(newIntervalTime) {
                    clearInterval(this.autoplayInterval)
                    this.autoplayIntervalTime = newIntervalTime
                    this.autoplay()
                },
            }))
        })
    </script>

    <div id="featured-products" class="overflow-hidden">
        <h2 class="text-center pt-8 font-bold text-[40px]">Sản Phẩm Nổi Bật</h2>
        <div class="owl-carousel featured-products w-full px-5">
            <?php
                $allProducts = [];
                foreach ($productsByCategory as $products) {
                    $allProducts = array_merge($allProducts, $products);
                }
                if (!empty($allProducts)) {
                    foreach ($allProducts as $product) {
                        ?>
                        <div class="w-full bg-white border shadow-md rounded-xl duration-500 hover:scale-105 hover:shadow-xl my-10">
                            <a class="text-center" href="#">
                                <img src="/assets/images/<?=$product['image']?>" alt="Product" class="h-80 m-auto object-cover rounded-t-xl p-10" />
                                <div class="px-4 py-3">
                                    <span class="text-gray-400 mr-3 uppercase text-xs">Brand</span>
                                    <p class="text-lg font-bold text-black truncate block capitalize"><?= htmlspecialchars($product['product_name']) ?></p>
                                    <div class="flex gap-0.5 items-center justify-center py-2.5">
                                        <svg class="h-4 w-4 shrink-0 fill-amber-400" viewBox="0 0 256 256">
                                            <path
                                                    d="M239.2 97.4A16.4 16.4.0 00224.6 86l-59.4-4.1-22-55.5A16.4 16.4.0 00128 16h0a16.4 16.4.0 00-15.2 10.4L90.4 82.2 31.4 86A16.5 16.5.0 0016.8 97.4 16.8 16.8.0 0022 115.5l45.4 38.4L53.9 207a18.5 18.5.0 007 19.6 18 18 0 0020.1.6l46.9-29.7h.2l50.5 31.9a16.1 16.1.0 008.7 2.6 16.5 16.5.0 0015.8-20.8l-14.3-58.1L234 115.5A16.8 16.8.0 00239.2 97.4z">
                                            </path>
                                        </svg>
                                        <svg class="h-4 w-4 shrink-0 fill-amber-400" viewBox="0 0 256 256">
                                            <path
                                                    d="M239.2 97.4A16.4 16.4.0 00224.6 86l-59.4-4.1-22-55.5A16.4 16.4.0 00128 16h0a16.4 16.4.0 00-15.2 10.4L90.4 82.2 31.4 86A16.5 16.5.0 0016.8 97.4 16.8 16.8.0 0022 115.5l45.4 38.4L53.9 207a18.5 18.5.0 007 19.6 18 18 0 0020.1.6l46.9-29.7h.2l50.5 31.9a16.1 16.1.0 008.7 2.6 16.5 16.5.0 0015.8-20.8l-14.3-58.1L234 115.5A16.8 16.8.0 00239.2 97.4z">
                                            </path>
                                        </svg>
                                        <svg class="h-4 w-4 shrink-0 fill-amber-400" viewBox="0 0 256 256">
                                            <path
                                                    d="M239.2 97.4A16.4 16.4.0 00224.6 86l-59.4-4.1-22-55.5A16.4 16.4.0 00128 16h0a16.4 16.4.0 00-15.2 10.4L90.4 82.2 31.4 86A16.5 16.5.0 0016.8 97.4 16.8 16.8.0 0022 115.5l45.4 38.4L53.9 207a18.5 18.5.0 007 19.6 18 18 0 0020.1.6l46.9-29.7h.2l50.5 31.9a16.1 16.1.0 008.7 2.6 16.5 16.5.0 0015.8-20.8l-14.3-58.1L234 115.5A16.8 16.8.0 00239.2 97.4z">
                                            </path>
                                        </svg>
                                        <svg class="h-4 w-4 shrink-0 fill-amber-400" viewBox="0 0 256 256">
                                            <path
                                                    d="M239.2 97.4A16.4 16.4.0 00224.6 86l-59.4-4.1-22-55.5A16.4 16.4.0 00128 16h0a16.4 16.4.0 00-15.2 10.4L90.4 82.2 31.4 86A16.5 16.5.0 0016.8 97.4 16.8 16.8.0 0022 115.5l45.4 38.4L53.9 207a18.5 18.5.0 007 19.6 18 18 0 0020.1.6l46.9-29.7h.2l50.5 31.9a16.1 16.1.0 008.7 2.6 16.5 16.5.0 0015.8-20.8l-14.3-58.1L234 115.5A16.8 16.8.0 00239.2 97.4z">
                                            </path>
                                        </svg>
                                        <svg class="h-4 w-4 shrink-0 fill-gray-300" viewBox="0 0 256 256">
                                            <path
                                                    d="M239.2 97.4A16.4 16.4.0 00224.6 86l-59.4-4.1-22-55.5A16.4 16.4.0 00128 16h0a16.4 16.4.0 00-15.2 10.4L90.4 82.2 31.4 86A16.5 16.5.0 0016.8 97.4 16.8 16.8.0 0022 115.5l45.4 38.4L53.9 207a18.5 18.5.0 007 19.6 18 18 0 0020.1.6l46.9-29.7h.2l50.5 31.9a16.1 16.1.0 008.7 2.6 16.5 16.5.0 0015.8-20.8l-14.3-58.1L234 115.5A16.8 16.8.0 00239.2 97.4z">
                                            </path>
                                        </svg>
                                    </div>
                                    <div class="flex items-center">
                                        <p class="text-lg font-semibold text-black cursor-auto my-3"><?= number_format($product['price'], 0, ',', '.') ?> VNĐ</p>
                                        <div class="ml-auto">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-bag-plus" viewBox="0 0 16 16">
                                                <path fill-rule="evenodd" d="M8 7.5a.5.5 0 0 1 .5.5v1.5H10a.5.5 0 0 1 0 1H8.5V12a.5.5 0 0 1-1 0v-1.5H6a.5.5 0 0 1 0-1h1.5V8a.5.5 0 0 1 .5-.5z" />
                                                <path d="M8 1a2.5 2.5 0 0 1 2.5 2.5V4h-5v-.5A2.5 2.5 0 0 1 8 1zm3.5 3v-.5a3.5 3.5 0 1 0-7 0V4H1v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4h-3.5zM2 5h12v9a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V5z" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <?php
                    }
                }
            ?>
        </div>
    </div>

    <div class="">
        <div class="flex justify-center mt-10 text-4xl font-bold">
            Why Us?
        </div>
        <div class="container px-5 py-12 mx-auto">
            <div class="flex flex-wrap text-center justify-center">
                <div class="p-4 md:w-1/4 sm:w-1/2">
                    <div class="px-4 py-6 transform transition duration-500 hover:scale-110">
                        <div class="flex justify-center">
                            <img src="https://image3.jdomni.in/banner/13062021/58/97/7C/E53960D1295621EFCB5B13F335_1623567851299.png?output-format=webp" class="w-32 mb-3">
                        </div>
                        <h2 class="title-font font-regular text-2xl text-gray-900">Latest Milling Machinery</h2>
                    </div>
                </div>
                <div class="p-4 md:w-1/4 sm:w-1/2">
                    <div class="px-4 py-6 transform transition duration-500 hover:scale-110">
                        <div class="flex justify-center">
                            <img src="https://image2.jdomni.in/banner/13062021/3E/57/E8/1D6E23DD7E12571705CAC761E7_1623567977295.png?output-format=webp" class="w-32 mb-3">
                        </div>
                        <h2 class="title-font font-regular text-2xl text-gray-900">Reasonable Rates</h2>
                    </div>
                </div>
                <div class="p-4 md:w-1/4 sm:w-1/2">
                    <div class="px-4 py-6 transform transition duration-500 hover:scale-110">
                        <div class="flex justify-center">
                            <img src="https://image3.jdomni.in/banner/13062021/16/7E/7E/5A9920439E52EF309F27B43EEB_1623568010437.png?output-format=webp" class="w-32 mb-3">
                        </div>
                        <h2 class="title-font font-regular text-2xl text-gray-900">Time Efficiency</h2>
                    </div>
                </div>
                <div class="p-4 md:w-1/4 sm:w-1/2">
                    <div class="px-4 py-6 transform transition duration-500 hover:scale-110">
                        <div class="flex justify-center">
                            <img src="https://image3.jdomni.in/banner/13062021/EB/99/EE/8B46027500E987A5142ECC1CE1_1623567959360.png?output-format=webp" class="w-32 mb-3">
                        </div>
                        <h2 class="title-font font-regular text-2xl text-gray-900">Expertise in Industry</h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="p-4 md:p-6 text-white bg-[#212529] rounded-xl">
        <div class="flex flex-wrap">
            <div class="w-full md:w-1/2 lg:w-1/4 py-8">
                <div class="md:border-r border-gray-200 px-12">
                    <h2 class="text-4xl text-[#909294] md:text-5xl font-semibold text-center">
                        2+
                    </h2>
                    <p class=" mb-2 text-center">Years Experiance</p>
                </div>
            </div>
            <div class="w-full md:w-1/2 lg:w-1/4 py-8">
                <div class="lg:border-r border-gray-200 px-12">
                    <h2 class="text-4xl text-[#909294] md:text-5xl  font-semibold text-center">
                        10+
                    </h2>
                    <p class=" mb-2 text-center">Happy Clients</p>
                </div>
            </div>
            <div class="w-full md:w-1/2 lg:w-1/4 py-8">
                <div class="md:border-r border-gray-200 px-12">
                    <h2 class="text-4xl text-[#909294] md:text-5xl  font-semibold text-center">
                        20+
                    </h2>
                    <p class=" mb-2 text-center">Projects Done</p>
                </div>
            </div>
            <div class="w-full md:w-1/2 lg:w-1/4 py-8">
                <div class="px-12">
                    <h2 class="text-4xl text-[#909294] md:text-5xl  font-semibold text-center">
                        10+
                    </h2>
                    <p class=" mb-2 text-center">Get Awards</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white py-24 sm:py-32">
        <div class="mx-auto w-full">
            <h2 class="flex justify-center mt-10 text-4xl font-bold">Trusted by companies across the globe</h2>
            <div class="mt-8 grid grid-cols-2 gap-1 overflow-hidden sm:grid-cols-4 sm:rounded-2xl lg:grid-cols-6">
                <div class="flex items-center justify-center bg-gray-500/5 p-8">
                    <img class="max-h-12 w-full object-contain" src="https://htmlwind.com/assets/images/logo/logoipsum-214.svg" alt="Logoipsum" />
                </div>
                <div class="flex items-center justify-center bg-gray-500/5 p-8">
                    <img class="max-h-12 w-full object-contain" src="https://htmlwind.com/assets/images/logo/logoipsum-221.svg" alt="Logoipsum" />
                </div>
                <div class="flex items-center justify-center bg-gray-500/5 p-8">
                    <img class="max-h-12 w-full object-contain" src="https://htmlwind.com/assets/images/logo/logoipsum-216.svg" alt="Logoipsum" />
                </div>
                <div class="flex items-center justify-center bg-gray-500/5 p-8">
                    <img class="max-h-12 w-full object-contain" src="https://htmlwind.com/assets/images/logo/logoipsum-317.svg" alt="Logoipsum" />
                </div>
                <div class="flex items-center justify-center bg-gray-500/5 p-8">
                    <img class="max-h-12 w-full object-contain" src="https://htmlwind.com/assets/images/logo/logoipsum-284.svg" alt="Logoipsum" />
                </div>
                <div class="flex items-center justify-center bg-gray-500/5 p-8">
                    <img class="max-h-12 w-full object-contain" src="https://htmlwind.com/assets/images/logo/logoipsum-311.svg" alt="Logoipsum" />
                </div>
                <div class="flex items-center justify-center bg-gray-500/5 p-8">
                    <img class="max-h-12 w-full object-contain" src="https://htmlwind.com/assets/images/logo/logoipsum-288.svg" alt="Logoipsum" />
                </div>
                <div class="flex items-center justify-center bg-gray-500/5 p-8">
                    <img class="max-h-12 w-full object-contain" src="https://htmlwind.com/assets/images/logo/logoipsum-263.svg" alt="Logoipsum" />
                </div>
                <div class="flex items-center justify-center bg-gray-500/5 p-8">
                    <img class="max-h-12 w-full object-contain" src="https://htmlwind.com/assets/images/logo/logoipsum-220.svg" alt="Logoipsum" />
                </div>
                <div class="flex items-center justify-center bg-gray-500/5 p-8">
                    <img class="max-h-12 w-full object-contain" src="https://htmlwind.com/assets/images/logo/logoipsum-211.svg" alt="Logoipsum" />
                </div>
                <div class="flex items-center justify-center bg-gray-500/5 p-8">
                    <img class="max-h-12 w-full object-contain" src="https://htmlwind.com/assets/images/logo/logoipsum-329.svg" alt="Logoipsum" />
                </div>
                <div class="flex items-center justify-center bg-gray-500/5 p-8">
                    <img class="max-h-12 w-full object-contain" src="https://htmlwind.com/assets/images/logo/logoipsum-325.svg" alt="Logoipsum" />
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Owl Carousel JS -->
<script>
    $('.featured-products').owlCarousel({
        items: 4,
        loop: true,
        margin: 30,
        autoplay: true,
        autoplayTimeout: 2500,
        autoplayHoverPause: true,
        responsive: {
            0: { items: 1 },
            768: { items: 2 },
            1024: { items: 4 }
        }
    });
</script>

<style>
    .owl-stage-outer{
        overflow: unset !important;
    }
</style>