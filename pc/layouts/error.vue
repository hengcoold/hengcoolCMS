<template>
  <div>
    <section class="bread-crumb-section">
      <img
        class="shape shape1"
        src="~/assets/images/error/1.png"
        alt="images_not_found"
      /><img
        class="shape shape2"
        src="~/assets/images/error/2.png"
        alt="images_not_found"
      />
      <div class="container">
        <div class="row">
          <div class="col-12">
            <h2 class="title text-center">Page Not Found</h2>
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb justify-content-center">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item active" aria-current="page">
                  <span>pages</span>
                </li>
              </ol>
            </nav>
          </div>
        </div>
      </div>
    </section>
    <!-- bread crumb section end --><!-- page not found section end -->
    <section class="page-not-found-section section-padding-y-200">
      <div class="container">
        <div class="row mb-n7">
          <div class="col-12 mb-7">
            <ul class="page-not-found">
              <li class="page-not-found-item">
                <img
                  src="assets/images/page-not-found/1.png"
                  alt="images_not_found"
                /><span class="number">4</span>
              </li>
              <li class="page-not-found-item">
                <img
                  src="assets/images/page-not-found/2.png"
                  alt="images_not_found"
                /><span class="number">0</span>
              </li>
              <li class="page-not-found-item">
                <img
                  src="assets/images/page-not-found/3.png"
                  alt="images_not_found"
                /><span class="number">4</span>
              </li>
            </ul>
          </div>
          <div class="col-lg-8 col-xl-6 mx-auto mb-7">
            <div class="page-not-found-content">
              <h3 class="title">
                Sorry!<small>This Page is Not Found.</small>
              </h3>
              <p>
                Lorem Ipsum is simply dummy text of others the printing and
                typesetting industry printer took a galley of type and scrambled
                make
              </p>
              <a href="index.html" class="btn btn-warning"
                ><i class="icofont-rounded-double-left"></i>Back To Home</a
              >
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>
<script>
export default {
  props: ['error']
};
</script>
