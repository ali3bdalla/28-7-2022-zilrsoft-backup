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
          <a href="#" class="login-panel"><i class="fa fa-user"></i>Login</a>
          <div class="lan-selector">
            <div
              class="ddOutOfVision"
              id="countries_msddHolder"
              style="height: 0px; overflow: hidden; position: absolute"
            >
              <select
                class="language_drop"
                name="countries"
                id="countries"
                style="width: 300px"
                tabindex="-1"
              >
                <option
                  value="yt"
                  data-image="/Web/template/img/flag-1.jpg'"
                  data-imagecss="flag yt"
                  data-title="English"
                >
                  English
                </option>
                <option
                  value="yu"
                  data-image="/Web/template/img/flag-2.jpg"
                  data-imagecss="flag yu"
                  data-title="Bangladesh"
                >
                  German
                </option>
              </select>
            </div>
            <div
              class="dd ddcommon borderRadius"
              id="countries_msdd"
              tabindex="0"
              style="width: 300px"
            >
              <div class="ddTitle borderRadiusTp">
                <span class="divider"></span
                ><span class="ddArrow arrowoff"></span
                ><span class="ddTitleText" id="countries_title"
                  ><img
                    src="/Web/template/img/flag-1.jpg"
                    class="flag yt fnone" /><span class="ddlabel">English</span
                  ><span class="description" style="display: none"></span
                ></span>
              </div>
              <input
                id="countries_titleText"
                type="text"
                autocomplete="off"
                class="text shadow borderRadius"
                style="display: none"
              />
              <div
                class="ddChild ddchild_ border shadow"
                id="countries_child"
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
                      src="/Web/template/img/flag-1.jpg"
                      class="flag yt fnone"
                    /><span class="ddlabel">English</span>
                    <div class="clear"></div>
                  </li>
                  <li class="enabled _msddli_" title="Bangladesh">
                    <img
                      src="/Web/template/img/flag-2.jpg"
                      class="flag yu fnone"
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
      <div class="inner-header">
        <div class="row">
          <div class="col-lg-2 col-md-2">
            <div class="logo">
              <a href="./index.html">
                <img src="/Web/template/img/logo.png" alt="" />
              </a>
            </div>
          </div>
          <div class="col-lg-7 col-md-7">
            <header-seach-input-component></header-seach-input-component>
          </div>
          <div class="col-lg-3 text-right col-md-3">
            <ul class="nav-right">
              <li class="heart-icon">
                <a href="#">
                  <i class="icon_heart_alt"></i>
                  <span>1</span>
                </a>
              </li>
              <li class="cart-icon">
                <a href="#">
                  <i class="icon_bag_alt"></i>
                  <span>3</span>
                </a>
                <div class="cart-hover">
                  <div class="select-items">
                    <table>
                      <tbody>
                        <tr>
                          <td class="si-pic">
                            <img
                              src="/Web/template/img/select-product-1.jpg"
                              alt=""
                            />
                          </td>
                          <td class="si-text">
                            <div class="product-selected">
                              <p>$60.00 x 1</p>
                              <h6>Kabino Bedside Table</h6>
                            </div>
                          </td>
                          <td class="si-close">
                            <i class="ti-close"></i>
                          </td>
                        </tr>
                        <tr>
                          <td class="si-pic">
                            <img
                              src="/Web/template/img/select-product-2.jpg"
                              alt=""
                            />
                          </td>
                          <td class="si-text">
                            <div class="product-selected">
                              <p>$60.00 x 1</p>
                              <h6>Kabino Bedside Table</h6>
                            </div>
                          </td>
                          <td class="si-close">
                            <i class="ti-close"></i>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <div class="select-total">
                    <span>total:</span>
                    <h5>$120.00</h5>
                  </div>
                  <div class="select-button">
                    <a href="#" class="primary-btn view-card">VIEW CARD</a>
                    <a href="#" class="primary-btn checkout-btn">CHECK OUT</a>
                  </div>
                </div>
              </li>
              <li class="cart-price">$150.00</li>
            </ul>
          </div>
        </div>
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
              class="slicknav_nav slicknav_hidden"
              aria-hidden="true"
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
                    href="#"
                    role="menuitem"
                    aria-haspopup="true"
                    tabindex="-1"
                    class="slicknav_item slicknav_row"
                    style="outline: none"
                    ><a href="#">Collection</a>
                    <span class="slicknav_arrow">►</span></a
                  >
                  <ul
                    class="dropdown slicknav_hidden"
                    role="menu"
                    aria-hidden="true"
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
                    href="#"
                    role="menuitem"
                    aria-haspopup="true"
                    tabindex="-1"
                    class="slicknav_item slicknav_row"
                    style="outline: none"
                    ><a href="#">Pages</a>
                    <span class="slicknav_arrow">►</span></a
                  >
                  <ul
                    class="dropdown slicknav_hidden"
                    role="menu"
                    aria-hidden="true"
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
export default {
  components: { HeaderSeachInputComponent },
};
</script>

<style>
</style>