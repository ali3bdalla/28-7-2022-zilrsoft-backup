(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[15],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/next/js/Web/Pages/Item/EditPage.vue?vue&type=script&lang=js&":
/*!****************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/next/js/Web/Pages/Item/EditPage.vue?vue&type=script&lang=js& ***!
  \****************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Layouts_WebLayout__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../Layouts/WebLayout */ "./resources/next/js/Web/Layouts/WebLayout.vue");
/* harmony import */ var _Components_Item_ItemFormComponent__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../Components/Item/ItemFormComponent */ "./resources/next/js/Web/Components/Item/ItemFormComponent.vue");
/* harmony import */ var _Mixins_LangMixin__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../Mixins/LangMixin */ "./resources/next/js/Mixins/LangMixin.js");
/* harmony import */ var _Mixins_ResponseMixin__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../Mixins/ResponseMixin */ "./resources/next/js/Mixins/ResponseMixin.js");
/* harmony import */ var _inertiajs_inertia__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! @inertiajs/inertia */ "./node_modules/@inertiajs/inertia/dist/index.js");
/* harmony import */ var _inertiajs_inertia__WEBPACK_IMPORTED_MODULE_4___default = /*#__PURE__*/__webpack_require__.n(_inertiajs_inertia__WEBPACK_IMPORTED_MODULE_4__);
//
//
//
//





/* harmony default export */ __webpack_exports__["default"] = ({
  name: 'CreatePage',
  components: {
    ItemFormComponent: _Components_Item_ItemFormComponent__WEBPACK_IMPORTED_MODULE_1__["default"]
  },
  mixins: [_Mixins_LangMixin__WEBPACK_IMPORTED_MODULE_2__["default"], _Mixins_ResponseMixin__WEBPACK_IMPORTED_MODULE_3__["default"]],
  layout: _Layouts_WebLayout__WEBPACK_IMPORTED_MODULE_0__["default"],
  props: {
    item: {
      type: Object,
      required: true
    }
  },
  data: function data() {
    return {
      form: {}
    };
  },
  methods: {
    submit: function submit(data) {
      // this.askUser().then(res => {
      //     if (res) {
      _inertiajs_inertia__WEBPACK_IMPORTED_MODULE_4__["Inertia"].patch(this.$appRoute('next-routes.api.items.update', this.item.id), JSON.parse(JSON.stringify(data)));
      this.handleResponse(this.$appRoute('next-routes.web.items.index'), this.$page.props.layoutLang.common.messages.success, this.$getLang('item_updated'), 'success', false); // }
      // })
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/next/js/Web/Pages/Item/EditPage.vue?vue&type=template&id=1ada5906&scoped=true&":
/*!********************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/next/js/Web/Pages/Item/EditPage.vue?vue&type=template&id=1ada5906&scoped=true& ***!
  \********************************************************************************************************************************************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "render", function() { return render; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return staticRenderFns; });
var render = function() {
  var _vm = this
  var _h = _vm.$createElement
  var _c = _vm._self._c || _h
  return _c("item-form-component", {
    attrs: { "$get-lang": _vm.$getLang, item: _vm.item },
    on: { submit: _vm.submit }
  })
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/next/js/Web/Pages/Item/EditPage.vue":
/*!*******************************************************!*\
  !*** ./resources/next/js/Web/Pages/Item/EditPage.vue ***!
  \*******************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _EditPage_vue_vue_type_template_id_1ada5906_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./EditPage.vue?vue&type=template&id=1ada5906&scoped=true& */ "./resources/next/js/Web/Pages/Item/EditPage.vue?vue&type=template&id=1ada5906&scoped=true&");
/* harmony import */ var _EditPage_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./EditPage.vue?vue&type=script&lang=js& */ "./resources/next/js/Web/Pages/Item/EditPage.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _EditPage_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _EditPage_vue_vue_type_template_id_1ada5906_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"],
  _EditPage_vue_vue_type_template_id_1ada5906_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  "1ada5906",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/next/js/Web/Pages/Item/EditPage.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/next/js/Web/Pages/Item/EditPage.vue?vue&type=script&lang=js&":
/*!********************************************************************************!*\
  !*** ./resources/next/js/Web/Pages/Item/EditPage.vue?vue&type=script&lang=js& ***!
  \********************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_EditPage_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./EditPage.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/next/js/Web/Pages/Item/EditPage.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_EditPage_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/next/js/Web/Pages/Item/EditPage.vue?vue&type=template&id=1ada5906&scoped=true&":
/*!**************************************************************************************************!*\
  !*** ./resources/next/js/Web/Pages/Item/EditPage.vue?vue&type=template&id=1ada5906&scoped=true& ***!
  \**************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_EditPage_vue_vue_type_template_id_1ada5906_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./EditPage.vue?vue&type=template&id=1ada5906&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/next/js/Web/Pages/Item/EditPage.vue?vue&type=template&id=1ada5906&scoped=true&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_EditPage_vue_vue_type_template_id_1ada5906_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_EditPage_vue_vue_type_template_id_1ada5906_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);