<!DOCTYPE html>
<html lang="en">
   <head>
      <title>Page title</title>
      <meta charset="utf-8">
      <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
      <link rel="preconnect" href="https://fonts.gstatic.com">
      <link href="https://fonts.googleapis.com/css2?family=Figtree:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">
      <link rel="stylesheet" href="{{ asset('auth_theme/css/tailwind/tailwind.min.css') }}">
      <link rel="icon" type="image/png" sizes="32x32" href="shuffle-for-tailwind.png">
      <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js" defer></script>
   </head>
   <body class="antialiased bg-body text-body font-body">
      <div class="">
         <section x-data="{ activeSlide: 1, slideCount: 3 }" class="p-4 bg-purple-500">
            <div class="px-4 sm:px-8 rounded-3xl bg-black md:py-16 py-8">
               <div class="container mx-auto pb-4 justify-between items-center md:px-4 md:flex">
                  <div class="flex">
                     <svg id="svg_31e9cf922af474cc4d746c8999a15707" width="8" height="8" viewbox="0 0 8 8" fill="none" xmlns="http://www.w3.org/2000/svg"></svg>
                     <span class="inline-block text-sm font-medium text-white">Testimonials</span>      
                  </div>
                  <div class="flex space-x-3">        
                    <a href="{{ route('login') }}" 
                        class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                        Connexion
                    </a>        
                    
                    <a href="{{ route('register.creator.form') }}" class="px-4 py-2 
                    bg-transparent border border-white text-white rounded-lg hover:bg-white hover:text-purple-600 transition-colors">Devenir Créateur</a>      
                </div>
               </div>
               <div class="border-t border-white border-opacity-25 md:pt-16 pt-4">
                  <div class="max-w-md mx-auto md:max-w-none">
                     <div class="flex flex-wrap items-center -mx-4">
                        <div class="w-full md:w-1/2 px-4 mb-12 md:mb-0">
                           <div class="max-w-lg mx-auto md:mr-0 overflow-hidden">
                              <div :style="'transform: translateX(-' + (activeSlide - 1) * 100 + '%)'" class="flex -mx-4 transition-transform duration-500" style="transform: translateX(-0%)">
                                 <div class="flex-shrink-0 px-4 w-full">
                                    <h4 class="font-medium text-white mb-10 lg:text-7xl text-5xl">"Connecte-toi et joue en direct avec ton créateur de contenu favori !"</h4>
                                    <span class="block text-xl font-medium text-white">Jenny Wilson</span>                  <span class="block mb-12 lg:mb-32 text-lg text-gray-500">Solar energy service</span>                
                                 </div>
                                 <div class="flex-shrink-0 px-4 w-full">
                                    <h4 class="text-3xl lg:text-4xl font-medium text-white mb-10">"Efficient, green tech, outstanding service"</h4>
                                    <span class="block text-xl font-medium text-white">John Jones</span>                  <span class="block mb-12 lg:mb-32 text-lg text-gray-500">CE0 Solar Company</span>                
                                 </div>
                                 <div class="flex-shrink-0 px-4 w-full">
                                    <h4 class="text-3xl lg:text-4xl font-medium text-white mb-10">"Flow transformed my energy use, efficient, green tech, outstanding service."</h4>
                                    <span class="block text-xl font-medium text-white">James Harrison</span>                  <span class="block mb-12 lg:mb-32 text-lg text-gray-500">Developer</span>                
                                 </div>
                              </div>
                              <div>
                                 <button x-on:click="activeSlide = activeSlide &gt; 1 ? activeSlide - 1 : slideCount" class="inline-block mr-4 text-gray-700 hover:text-lime-500">
                                    <svg id="svg_2d4362c36299798b3e0bb1380672098e" width="32" height="32" viewbox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg"></svg>
                                 </button>
                                 <button x-on:click="activeSlide = activeSlide &lt; slideCount ? activeSlide + 1 : 1" class="inline-block text-gray-700 hover:text-lime-500">
                                    <svg id="svg_f2ed2725430ba83c5204e48458305a39" width="32" height="32" viewbox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg"></svg>
                                 </button>
                              </div>
                           </div>
                        </div>
                        <div class="w-full md:w-1/2 px-4">
                           <div class="max-w-lg mx-auto md:mr-0 overflow-hidden">
                              <div :style="'transform: translateX(-' + (activeSlide - 1) * 100 + '%)'" class="-mx-4 transition-transform duration-500 flex" style="transform: translateX(-0%)">                
                                
                                <img class="flex-shrink-0 px-4 block w-full" src="{{asset('auth_theme/images/pexels.jpg') }}
" alt="">                
                                
                                <img class="flex-shrink-0 w-full px-4 hidden block" src="flow-assets/testimonials/photo-lg.png" alt="">               
                                
                                <img class="block flex-shrink-0 w-full px-4 hidden" src="flow-assets/testimonials/photo-lg.png" alt="">              
                            </div>
                           </div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </section>
         <section class="bg-purple-500">
            <div class="container mx-auto px-4">
               <div class="max-w-xl mx-auto xl:max-w-none">
                  <div class="relative flex flex-wrap py-12 items-center justify-between">
                     <a href="#" class="inline-block">
                     <img class="h-8" src="flow-assets/logos/logo-white-flow.svg" alt="">
                     </a>
                     <ul class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 hidden xl:flex">
                        <li class="mr-16"><a class="inline-block text-white hover:text-lime-500 font-medium" href="#">Solutions</a></li>
                        <li class="mr-16"><a class="inline-block text-white hover:text-lime-500 font-medium" href="#">How it works</a></li>
                        <li class="mr-16"><a class="inline-block text-white hover:text-lime-500 font-medium" href="#">Pricing</a></li>
                        <li class="mr-16"><a class="inline-block text-white hover:text-lime-500 font-medium" href="#">About</a></li>
                        <li><a class="inline-block text-white hover:text-lime-500 font-medium" href="#">Blog</a></li>
                     </ul>
                     <div class="flex items-center justify-end">
                        <a href="#" class="inline-block mr-4 text-white opacity-80 hover:opacity-100">
                           <svg width="20" height="20" viewbox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <g clip-path="url(#clip0_230_4832)">
                                 <path d="M11.5481 19.9999V10.8776H14.6088L15.068 7.32147H11.5481V5.05138C11.5481 4.02211 11.8327 3.32067 13.3104 3.32067L15.1919 3.3199V0.139138C14.8665 0.0968538 13.7496 -9.15527e-05 12.4496 -9.15527e-05C9.735 -9.15527e-05 7.87654 1.65687 7.87654 4.69918V7.32147H4.80652V10.8776H7.87654V19.9999H11.5481Z" fill="currentColor"></path>
                              </g>
                           </svg>
                        </a>
                        <a href="#" class="inline-block mr-4 text-white opacity-80 hover:opacity-100">
                           <svg width="24" height="24" viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path d="M7.8 2H16.2C19.4 2 22 4.6 22 7.8V16.2C22 17.7383 21.3889 19.2135 20.3012 20.3012C19.2135 21.3889 17.7383 22 16.2 22H7.8C4.6 22 2 19.4 2 16.2V7.8C2 6.26174 2.61107 4.78649 3.69878 3.69878C4.78649 2.61107 6.26174 2 7.8 2ZM7.6 4C6.64522 4 5.72955 4.37928 5.05442 5.05442C4.37928 5.72955 4 6.64522 4 7.6V16.4C4 18.39 5.61 20 7.6 20H16.4C17.3548 20 18.2705 19.6207 18.9456 18.9456C19.6207 18.2705 20 17.3548 20 16.4V7.6C20 5.61 18.39 4 16.4 4H7.6ZM17.25 5.5C17.5815 5.5 17.8995 5.6317 18.1339 5.86612C18.3683 6.10054 18.5 6.41848 18.5 6.75C18.5 7.08152 18.3683 7.39946 18.1339 7.63388C17.8995 7.8683 17.5815 8 17.25 8C16.9185 8 16.6005 7.8683 16.3661 7.63388C16.1317 7.39946 16 7.08152 16 6.75C16 6.41848 16.1317 6.10054 16.3661 5.86612C16.6005 5.6317 16.9185 5.5 17.25 5.5ZM12 7C13.3261 7 14.5979 7.52678 15.5355 8.46447C16.4732 9.40215 17 10.6739 17 12C17 13.3261 16.4732 14.5979 15.5355 15.5355C14.5979 16.4732 13.3261 17 12 17C10.6739 17 9.40215 16.4732 8.46447 15.5355C7.52678 14.5979 7 13.3261 7 12C7 10.6739 7.52678 9.40215 8.46447 8.46447C9.40215 7.52678 10.6739 7 12 7ZM12 9C11.2044 9 10.4413 9.31607 9.87868 9.87868C9.31607 10.4413 9 11.2044 9 12C9 12.7956 9.31607 13.5587 9.87868 14.1213C10.4413 14.6839 11.2044 15 12 15C12.7956 15 13.5587 14.6839 14.1213 14.1213C14.6839 13.5587 15 12.7956 15 12C15 11.2044 14.6839 10.4413 14.1213 9.87868C13.5587 9.31607 12.7956 9 12 9Z" fill="currentColor"></path>
                           </svg>
                        </a>
                        <a href="#" class="inline-block text-white opacity-80 hover:opacity-100">
                           <svg width="24" height="24" viewbox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                              <path d="M19 3C19.5304 3 20.0391 3.21071 20.4142 3.58579C20.7893 3.96086 21 4.46957 21 5V19C21 19.5304 20.7893 20.0391 20.4142 20.4142C20.0391 20.7893 19.5304 21 19 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V5C3 4.46957 3.21071 3.96086 3.58579 3.58579C3.96086 3.21071 4.46957 3 5 3H19ZM18.5 18.5V13.2C18.5 12.3354 18.1565 11.5062 17.5452 10.8948C16.9338 10.2835 16.1046 9.94 15.24 9.94C14.39 9.94 13.4 10.46 12.92 11.24V10.13H10.13V18.5H12.92V13.57C12.92 12.8 13.54 12.17 14.31 12.17C14.6813 12.17 15.0374 12.3175 15.2999 12.5801C15.5625 12.8426 15.71 13.1987 15.71 13.57V18.5H18.5ZM6.88 8.56C7.32556 8.56 7.75288 8.383 8.06794 8.06794C8.383 7.75288 8.56 7.32556 8.56 6.88C8.56 5.95 7.81 5.19 6.88 5.19C6.43178 5.19 6.00193 5.36805 5.68499 5.68499C5.36805 6.00193 5.19 6.43178 5.19 6.88C5.19 7.81 5.95 8.56 6.88 8.56ZM8.27 18.5V10.13H5.5V18.5H8.27Z" fill="currentColor"></path>
                           </svg>
                        </a>
                     </div>
                     <div class="w-full xl:hidden mt-8">
                        <ul class="flex flex-wrap -mb-4 xs:justify-between">
                           <li class="mb-4 mr-8"><a class="inline-block text-white hover:text-lime-500 font-medium" href="#">Solutions</a></li>
                           <li class="mb-4 mr-8"><a class="inline-block text-white hover:text-lime-500 font-medium" href="#">How it works</a></li>
                           <li class="mb-4 mr-8"><a class="inline-block text-white hover:text-lime-500 font-medium" href="#">Pricing</a></li>
                           <li class="mb-4 mr-8"><a class="inline-block text-white hover:text-lime-500 font-medium" href="#">About</a></li>
                           <li class="mb-4"><a class="inline-block text-white hover:text-lime-500 font-medium" href="#">Blog</a></li>
                        </ul>
                     </div>
                  </div>
               </div>
            </div>
         </section>
      </div>
   </body>
</html>