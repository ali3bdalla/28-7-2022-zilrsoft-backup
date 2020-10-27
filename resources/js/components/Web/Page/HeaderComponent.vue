<template>
  <header class="header-section">
    <div class="header-top">
      <div class="container">
        <div class="ht-left">
          <div class="mail-service">
            <i class="fa fa-envelope"></i>
            hello.colorlib@gmail.com
          </div>
          <div class="phone-service">
            <i class="fa fa-phone"></i>
            +65 11.188.888
          </div>
        </div>
        <div class="ht-right">
          <a class="login-panel" href="#"><i class="fa fa-user"></i>Login</a>
          <div class="lan-selector">
            <div
                id="countries_msddHolder"
                class="ddOutOfVision"
                style="height: 0px; overflow: hidden; position: absolute"
            >
              <select
                  id="countries"
                  class="language_drop"
                  name="countries"
                  style="width: 300px"
                  tabindex="-1"
              >
                <!--                <option-->
                <!--                  value="yt"-->
                <!--                  data-image="/Web/template/img/flag-1.jpg'"-->
                <!--                  data-imagecss="flag yt"-->
                <!--                  data-title="English"-->
                <!--                >-->
                <!--                  English-->
                <!--                </option>-->
                <option
                    data-image="/Web/template/img/flag-2.jpg"
                    data-imagecss="flag yu"
                    data-title="Bangladesh"
                    value="yu"
                >
                  German
                </option>
              </select>
            </div>
            <div
                id="countries_msdd"
                class="dd ddcommon borderRadius"
                style="width: 300px"
                tabindex="0"
            >
              <div class="ddTitle borderRadiusTp">
                <span class="divider"></span
                ><span class="ddArrow arrowoff"></span
              ><span id="countries_title" class="ddTitleText"
              ><img
                  class="flag yt fnone"
                  src="/Web/template/img/flag-1.jpg"/><span class="ddlabel">English</span
              ><span class="description" style="display: none"></span
              ></span>
              </div>
              <input
                  id="countries_titleText"
                  autocomplete="off"
                  class="text shadow borderRadius"
                  style="display: none"
                  type="text"
              />
              <div
                  id="countries_child"
                  class="ddChild ddchild_ border shadow"
                  style="
                  z-index: 9999;
                  display: none;
                  position: absolute;
                  visibility: visible;
                  height: 51px;
                "
              >
                <ul>
                  <li class="enabled _msddli_ selected" title="English">
                    <img
                        class="flag yt fnone"
                        src="/Web/template/img/flag-1.jpg"
                    /><span class="ddlabel">English</span>
                    <div class="clear"></div>
                  </li>
                  <li class="enabled _msddli_" title="Bangladesh">
                    <img
                        class="flag yu fnone"
                        src="/Web/template/img/flag-2.jpg"
                    /><span class="ddlabel">German</span>
                    <div class="clear"></div>
                  </li>
                </ul>
              </div>
            </div>
          </div>
          <div class="top-social">
            <a href="#"><i class="ti-facebook"></i></a>
            <a href="#"><i class="ti-twitter-alt"></i></a>
            <a href="#"><i class="ti-linkedin"></i></a>
            <a href="#"><i class="ti-pinterest"></i></a>
          </div>
        </div>
      </div>
    </div>

    <div class="container">
      <div class="inner-header m-0 p-0 flex  items-center justify-center">
        <!--        <div class="row">-->
        <div class="w-3/12">
          <div class="logo">
            <a href="./index.html">
              <img alt="" src="/Web/template/img/logo.png"/>
            </a>
          </div>
        </div>
        <div class="w-7/12">
          <header-seach-input-component></header-seach-input-component>
        </div>
        <HeaderQuickCartComponent></HeaderQuickCartComponent>
        <!--        </div>-->
      </div>
    </div>

    <div class="nav-item">
      <div class="container">
        <div class="nav-depart">
          <div class="depart-btn">
            <i class="ti-menu"></i>
            <span>All departments</span>
            <ul class="depart-hover">
              <li class="active"><a href="#">Women’s Clothing</a></li>
              <li><a href="#">Men’s Clothing</a></li>
              <li><a href="#">Underwear</a></li>
              <li><a href="#">Kid's Clothing</a></li>
              <li><a href="#">Brand Fashion</a></li>
              <li><a href="#">Accessories/Shoes</a></li>
              <li><a href="#">Luxury Brands</a></li>
              <li><a href="#">Brand Outdoor Apparel</a></li>
            </ul>
          </div>
        </div>
        <nav class="nav-menu mobile-menu">
          <ul>
            <li class="active"><a href="./index.html">Home</a></li>
            <li><a href="./shop.html">Shop</a></li>
            <li>
              <a href="#">Collection</a>
              <ul class="dropdown">
                <li><a href="#">Men's</a></li>
                <li><a href="#">Women's</a></li>
                <li><a href="#">Kid's</a></li>
              </ul>
            </li>
            <li><a href="./blog.html">Blog</a></li>
            <li><a href="./contact.html">Contact</a></li>
            <li>
              <a href="#">Pages</a>
              <ul class="dropdown">
                <li><a href="./blog-details.html">Blog Details</a></li>
                <li><a href="./shopping-cart.html">Shopping Cart</a></li>
                <li><a href="./check-out.html">Checkout</a></li>
                <li><a href="./faq.html">Faq</a></li>
                <li><a href="./register.html">Register</a></li>
                <li><a href="./login.html">Login</a></li>
              </ul>
            </li>
          </ul>
        </nav>
        <div id="mobile-menu-wrap">
          <div class="slicknav_menu">
            <nav
                aria-hidden="true"
                class="slicknav_nav slicknav_hidden"
                role="menu"
                style="display: none"
            >
              <ul>
                <li class="active">
                  <a href="./index.html" role="menuitem">Home</a>
                </li>
                <li><a href="./shop.html" role="menuitem">Shop</a></li>
                <li class="slicknav_collapsed slicknav_parent">
                  <a
                      aria-haspopup="true"
                      class="slicknav_item slicknav_row"
                      href="#"
                      role="menuitem"
                      style="outline: none"
                      tabindex="-1"
                  ><a href="#">Collection</a>
                    <span class="slicknav_arrow">►</span></a
                  >
                  <ul
                      aria-hidden="true"
                      class="dropdown slicknav_hidden"
                      role="menu"
                      style="display: none"
                  >
                    <li><a href="#" role="menuitem" tabindex="-1">Men's</a></li>
                    <li>
                      <a href="#" role="menuitem" tabindex="-1">Women's</a>
                    </li>
                    <li><a href="#" role="menuitem" tabindex="-1">Kid's</a></li>
                  </ul>
                </li>
                <li><a href="./blog.html" role="menuitem">Blog</a></li>
                <li><a href="./contact.html" role="menuitem">Contact</a></li>
                <li class="slicknav_collapsed slicknav_parent">
                  <a
                      aria-haspopup="true"
                      class="slicknav_item slicknav_row"
                      href="#"
                      role="menuitem"
                      style="outline: none"
                      tabindex="-1"
                  ><a href="#">Pages</a>
                    <span class="slicknav_arrow">►</span></a
                  >
                  <ul
                      aria-hidden="true"
                      class="dropdown slicknav_hidden"
                      role="menu"
                      style="display: none"
                  >
                    <li>
                      <a
                          href="./blog-details.html"
                          role="menuitem"
                          tabindex="-1"
                      >Blog Details</a
                      >
                    </li>
                    <li>
                      <a
                          href="./shopping-cart.html"
                          role="menuitem"
                          tabindex="-1"
                      >Shopping Cart</a
                      >
                    </li>
                    <li>
                      <a href="./check-out.html" role="menuitem" tabindex="-1"
                      >Checkout</a
                      >
                    </li>
                    <li>
                      <a href="./faq.html" role="menuitem" tabindex="-1">Faq</a>
                    </li>
                    <li>
                      <a href="./register.html" role="menuitem" tabindex="-1"
                      >Register</a
                      >
                    </li>
                    <li>
                      <a href="./login.html" role="menuitem" tabindex="-1"
                      >Login</a
                      >
                    </li>
                  </ul>
                </li>
              </ul>
            </nav>
          </div>
        </div>
      </div>
    </div>
  </header>
</template>

<script>
import HeaderSeachInputComponent from "./HeaderSeachInputComponent";
import HeaderQuickCartComponent from "./../Cart/HeaderQuickCartComponent";

export default {
  components: {HeaderSeachInputComponent, HeaderQuickCartComponent},
};
</script>

<style>
</style>