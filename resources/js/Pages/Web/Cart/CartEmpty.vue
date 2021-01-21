<template>
  <div>
    <div class=" text-center flex items-center flex-col justify-center">
      <div class="mb-3">
        <svg
          class="w-32 text-gray-400 h-32"
          fill="none"
          stroke="currentColor"
          viewBox="0 0 24 24"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"
            stroke-linecap="round"
            stroke-linejoin="round"
            stroke-width="2"
          />
        </svg>
      </div>
     <div>
        <h1 class="text-4xl text-gray-600">{{$page.$t.cart.cart_empty}}</h1>
     </div>
     <div class="mt-5">
         <inertia-link href="/web" class="bg-gray-800 text-white px-4 py-2 rounded-full shadow-lg text-lg uppercase">{{$page.$t.cart.shopping_here}}</inertia-link>
     </div>
    </div>
  </div>
</template>
