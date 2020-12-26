<template>
  <section class="hero-section">
    <div class="hero-items owl-carousel owl-loaded owl-drag">
      <div class="owl-stage-outer">
        <div
          class="owl-stage"
          style="
            transform: translate3d(-2880px, 0px, 0px);
            transition: all 0s ease 0s;
            width: 8640px;
          "
        >
          <div class="owl-item cloned" style="width: 1440px">


            <div
              class="single-hero-items set-bg"
              data-setbg="/Web/template/img/side/1.jpeg"
              style="background-size: cover;background-image: url('/Web/template/img/side/1.jpeg');"
            >
              <div class="container">
                <div class="row">
                  <div class="col-lg-5"></div>
                </div>
              </div>
            </div>
          </div>

          <div class="owl-item cloned" style="width: 1440px">
            <div
              class="single-hero-items set-bg"
              data-setbg="Web/template/img/side/2.jpeg"
              style="background-size: cover;background-image: url('/Web/template/img/side/2.jpeg;"
            >
              <div class="container">
                <div class="row">
                  <div class="col-lg-5"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
export default {
    props:["items"],
    created()
    {
        // console.log(this.items)
    }
};
</script>

<style>
</style>>