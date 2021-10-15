<template>
  <div>
    <div class="map spad">
      <div class="container">
        <div class="map-inner">

          <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d497.91496617728825!2d43.496877770713866!3d25.870506428505!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x1583ca8e030140e5%3A0xcc0c78393eacaf92!2sBait%20Almesbar%20Trading%20Est.!5e0!3m2!1sen!2ssa!4v1611512746520!5m2!1sen!2ssa"
            height="610"
            style="border: 0"
            allowfullscreen=""
          >
          </iframe>
          <div class="icon">
            <i class="fa fa-map-marker"></i>
          </div>
        </div>
      </div>
    </div>

    <section class="contact-section spad">
      <div class="container">
        <div class="row">
          <div class="col-lg-5">
            <div class="contact-title">
              <h4>{{ $page.props.$t.common.contact_us }}</h4>
              <!-- <p>
               {{ $page.props.$t.common.contact_us_message }}
              </p> -->
            </div>
            <div class="contact-widget">
              <div class="cw-item">
                <div class="ci-icon">
                  <i class="ti-location-pin"></i>
                </div>
                <div class="ci-text">
                  <span>{{ $page.props.$t.profile.address }}:</span>
                  <p>{{ $page.props.app.msbrshop.address[$page.props.active_locale] }}</p>
                </div>
              </div>
              <div class="cw-item">
                <div class="ci-icon">
                  <i class="ti-mobile"></i>
                </div>
                <div class="ci-text">
                  <span>{{ $page.props.$t.profile.tel }}:</span>
                  <p class="direction: ltr !important">{{ $page.props.app.msbrshop.phone_number }}</p>
                </div>
              </div>
              <div class="cw-item">
                <div class="ci-icon">
                  <i class="fa fa-message"></i>
                </div>
                <div class="ci-text">
                  <span>{{ $page.props.$t.profile.email_address }}:</span>
                  <p>{{ $page.props.app.msbrshop.email_address }}</p>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-6 offset-lg-1" style="margin-right:0px !important">
            <div class="contact-form">
              <div class="leave-comment">
                <h4>{{$page.props.$t.common.keep_message}}</h4>
                <!-- <p>Our staff will call back later and answer your questions.</p> -->
                <form action="#" class="comment-form">
                  <div class="row">
                    <div class="col-lg-6">
                      <input type="text" :placeholder="$page.props.$t.contact.name" />
                    </div>
                    <div class="col-lg-6">
                      <input type="text" :placeholder="$page.props.$t.contact.email" />
                    </div>
                    <div class="col-lg-12">
                      <textarea :placeholder="$page.props.$t.contact.message"></textarea>
                      <button type="submit" class="site-btn w-full">
                        {{  $page.props.$t.contact.send }}
                      </button>
                    </div>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import WebAppLayout from '../../Layouts/WebAppLayout'
export default {
  layout: WebAppLayout
}
</script>

<style>
</style>
