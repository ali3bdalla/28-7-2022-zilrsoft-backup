(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[12],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/next/js/Web/Pages/Daily/IndexPage.vue?vue&type=script&lang=js&":
/*!******************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/next/js/Web/Pages/Daily/IndexPage.vue?vue&type=script&lang=js& ***!
  \******************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _Mixins_LangMixin__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../Mixins/LangMixin */ "./resources/next/js/Mixins/LangMixin.js");
/* harmony import */ var _Mixins_ResponseMixin__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../../Mixins/ResponseMixin */ "./resources/next/js/Mixins/ResponseMixin.js");
/* harmony import */ var _Components_DataGrid_DataGridComponent_vue__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../Components/DataGrid/DataGridComponent.vue */ "./resources/next/js/Web/Components/DataGrid/DataGridComponent.vue");
/* harmony import */ var _Components_Utility_DisplayMoney__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../Components/Utility/DisplayMoney */ "./resources/next/js/Web/Components/Utility/DisplayMoney.vue");
/* harmony import */ var _Layouts_WebLayout__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../../Layouts/WebLayout */ "./resources/next/js/Web/Layouts/WebLayout.vue");
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//




 // import OrderStatusTagComponent from '../../Components/Order/OrderStatusTagComponent'

/* harmony default export */ __webpack_exports__["default"] = ({
  name: 'IndexPage',
  components: {
    DataGridComponent: _Components_DataGrid_DataGridComponent_vue__WEBPACK_IMPORTED_MODULE_2__["default"],
    DisplayMoney: _Components_Utility_DisplayMoney__WEBPACK_IMPORTED_MODULE_3__["default"] // OrderStatusTagComponent

  },
  mixins: [_Mixins_LangMixin__WEBPACK_IMPORTED_MODULE_0__["default"], _Mixins_ResponseMixin__WEBPACK_IMPORTED_MODULE_1__["default"]],
  layout: _Layouts_WebLayout__WEBPACK_IMPORTED_MODULE_4__["default"],
  props: ['statues', 'types'],
  data: function data() {
    return {
      filters: {
        statuses: [] // shipping_methods: []

      },
      tableState: {}
    };
  },
  methods: {
    statusChanged: function statusChanged(e) {
      this.filters.statuses = e;
    },
    tableStateHasBeenReset: function tableStateHasBeenReset(state) {
      if (state.filters) {
        this.filters = state.filters;
      }

      this.tableState = state;
    },
    tableStateChanged: function tableStateChanged(state) {
      this.tableState = state;
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/next/js/Web/Pages/Daily/IndexPage.vue?vue&type=template&id=335f9313&scoped=true&":
/*!**********************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/next/js/Web/Pages/Daily/IndexPage.vue?vue&type=template&id=335f9313&scoped=true& ***!
  \**********************************************************************************************************************************************************************************************************************************/
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
  return _c(
    "data-grid-component",
    {
      attrs: {
        "actions-link": _vm.$appRoute("next-routes.web.items.index"),
        "addtional-sorded-by-items": [],
        endpoint: _vm.$appRoute("next-routes.api.daily.index"),
        filters: _vm.filters,
        "has-create-button": true
      },
      on: { tableStateHasBeenReset: _vm.tableStateHasBeenReset },
      scopedSlots: _vm._u([
        {
          key: "title",
          fn: function() {
            return [
              _vm._v(
                "\n        " +
                  _vm._s(_vm.$page.props.layoutLang.navbar.daily) +
                  "\n    "
              )
            ]
          },
          proxy: true
        },
        {
          key: "additional_header_filters",
          fn: function() {
            return [
              _c(
                "el-select",
                {
                  attrs: {
                    placeholder: _vm.$getLang("status"),
                    filterable: "",
                    multiple: "",
                    size: "large"
                  },
                  on: { change: _vm.statusChanged },
                  model: {
                    value: _vm.filters.statuses,
                    callback: function($$v) {
                      _vm.$set(_vm.filters, "statuses", $$v)
                    },
                    expression: "filters.statuses"
                  }
                },
                _vm._l(_vm.$getLang("statuses"), function(item, index) {
                  return _c("el-option", {
                    key: index,
                    attrs: { label: item, value: index }
                  })
                }),
                1
              )
            ]
          },
          proxy: true
        },
        {
          key: "tableView",
          fn: function(ref) {
            var tableState = ref.tableState
            return [
              _c("el-table-column", {
                attrs: {
                  label: _vm.$page.props.layoutLang.common.id,
                  align: "center",
                  "header-align": "center",
                  prop: "id",
                  width: "80"
                }
              }),
              _vm._v(" "),
              _c("el-table-column", {
                attrs: {
                  label: _vm.$getLang("barcode"),
                  align: "center",
                  "header-align": "center",
                  prop: "barcode",
                  width: "130"
                }
              }),
              _vm._v(" "),
              _c("el-table-column", {
                attrs: {
                  label: _vm.$getLang("name"),
                  align: "center",
                  "header-align": "center",
                  prop: "name",
                  width: "330"
                },
                scopedSlots: _vm._u(
                  [
                    {
                      key: "default",
                      fn: function(scope) {
                        return [
                          _c("div", [_vm._v(_vm._s(scope.row.ar_name))]),
                          _vm._v(" "),
                          _c("div", [_vm._v(_vm._s(scope.row.name))])
                        ]
                      }
                    }
                  ],
                  null,
                  true
                )
              }),
              _vm._v(" "),
              _c("el-table-column", {
                attrs: {
                  label: _vm.$getLang("price"),
                  align: "center",
                  "header-align": "center",
                  prop: "price",
                  width: "100"
                },
                scopedSlots: _vm._u(
                  [
                    {
                      key: "default",
                      fn: function(ref) {
                        var row = ref.row
                        return [
                          _c("display-money", { attrs: { money: row.price } })
                        ]
                      }
                    }
                  ],
                  null,
                  true
                )
              }),
              _vm._v(" "),
              _c("el-table-column", {
                attrs: {
                  label: _vm.$getLang("price_including_tax"),
                  align: "center",
                  "header-align": "center",
                  prop: "price_with_tax",
                  width: "120"
                },
                scopedSlots: _vm._u(
                  [
                    {
                      key: "default",
                      fn: function(ref) {
                        var row = ref.row
                        return [
                          _c("display-money", {
                            attrs: { money: row.price_with_tax }
                          })
                        ]
                      }
                    }
                  ],
                  null,
                  true
                )
              }),
              _vm._v(" "),
              _c("el-table-column", {
                attrs: {
                  label: _vm.$getLang("cost"),
                  align: "center",
                  "header-align": "center",
                  prop: "cost",
                  width: "80"
                },
                scopedSlots: _vm._u(
                  [
                    {
                      key: "default",
                      fn: function(ref) {
                        var row = ref.row
                        return [
                          _c("display-money", { attrs: { money: row.cost } })
                        ]
                      }
                    }
                  ],
                  null,
                  true
                )
              }),
              _vm._v(" "),
              _c("el-table-column", {
                attrs: {
                  label: _vm.$getLang("online_offer_price"),
                  align: "center",
                  "header-align": "center",
                  prop: "online_offer_price",
                  width: "80"
                },
                scopedSlots: _vm._u(
                  [
                    {
                      key: "default",
                      fn: function(ref) {
                        var row = ref.row
                        return [
                          _c("display-money", {
                            attrs: { money: row.online_offer_price }
                          })
                        ]
                      }
                    }
                  ],
                  null,
                  true
                )
              }),
              _vm._v(" "),
              _c("el-table-column", {
                attrs: {
                  label: _vm.$getLang("available_qty"),
                  align: "center",
                  "header-align": "center",
                  prop: "available_qty",
                  width: "80"
                },
                scopedSlots: _vm._u(
                  [
                    {
                      key: "default",
                      fn: function(ref) {
                        var row = ref.row
                        return [
                          _vm._v(
                            "\n                " +
                              _vm._s(row.available_qty) +
                              "\n            "
                          )
                        ]
                      }
                    }
                  ],
                  null,
                  true
                )
              }),
              _vm._v(" "),
              _c("el-table-column", {
                attrs: {
                  label: _vm.$getLang("status"),
                  align: "center",
                  "header-align": "center",
                  width: "80"
                },
                scopedSlots: _vm._u(
                  [
                    {
                      key: "default",
                      fn: function(ref) {
                        var row = ref.row
                        return [
                          _c(
                            "div",
                            {
                              staticClass:
                                "flex flex-col gap-2 flex-wrap items-center"
                            },
                            [
                              row.is_need_serial
                                ? _c(
                                    "el-tag",
                                    {
                                      attrs: {
                                        effect: "dark",
                                        size: "small",
                                        type: "primary"
                                      }
                                    },
                                    [
                                      _vm._v(
                                        "\n                        " +
                                          _vm._s(
                                            _vm.$getLang("statuses")[
                                              "is_need_serial"
                                            ]
                                          ) +
                                          "\n                    "
                                      )
                                    ]
                                  )
                                : _vm._e(),
                              _vm._v(" "),
                              row.is_service
                                ? _c(
                                    "el-tag",
                                    {
                                      attrs: {
                                        effect: "dark",
                                        size: "small",
                                        type: "info"
                                      }
                                    },
                                    [
                                      _vm._v(
                                        "\n                        " +
                                          _vm._s(
                                            _vm.$getLang("statuses")[
                                              "is_service"
                                            ]
                                          ) +
                                          "\n                    "
                                      )
                                    ]
                                  )
                                : _vm._e(),
                              _vm._v(" "),
                              row.is_published
                                ? _c(
                                    "el-tag",
                                    {
                                      attrs: {
                                        effect: "dark",
                                        size: "small",
                                        type: "success"
                                      }
                                    },
                                    [
                                      _vm._v(
                                        "\n                        " +
                                          _vm._s(
                                            _vm.$getLang("statuses")[
                                              "is_online"
                                            ]
                                          ) +
                                          "\n                    "
                                      )
                                    ]
                                  )
                                : _vm._e(),
                              _vm._v(" "),
                              row.is_expense
                                ? _c(
                                    "el-tag",
                                    {
                                      attrs: {
                                        effect: "dark",
                                        size: "small",
                                        type: ""
                                      }
                                    },
                                    [
                                      _vm._v(
                                        "\n                        " +
                                          _vm._s(
                                            _vm.$getLang("statuses")[
                                              "is_expense"
                                            ]
                                          ) +
                                          "\n                    "
                                      )
                                    ]
                                  )
                                : _vm._e(),
                              _vm._v(" "),
                              row.is_kit
                                ? _c(
                                    "el-tag",
                                    {
                                      attrs: {
                                        effect: "dark",
                                        size: "small",
                                        type: "warning"
                                      }
                                    },
                                    [
                                      _vm._v(
                                        "\n                        " +
                                          _vm._s(
                                            _vm.$getLang("statuses")["is_kit"]
                                          ) +
                                          "\n                    "
                                      )
                                    ]
                                  )
                                : _vm._e()
                            ],
                            1
                          )
                        ]
                      }
                    }
                  ],
                  null,
                  true
                )
              }),
              _vm._v(" "),
              _c("el-table-column", {
                attrs: {
                  label: _vm.$page.props.layoutLang.common.creator,
                  align: "center",
                  "header-align": "center",
                  prop: "created_by.locale_name",
                  width: "120"
                },
                scopedSlots: _vm._u(
                  [
                    {
                      key: "default",
                      fn: function(ref) {
                        var row = ref.row
                        return [
                          _vm._v(
                            "\n                " +
                              _vm._s(row.created_by.locale_name) +
                              "\n            "
                          )
                        ]
                      }
                    }
                  ],
                  null,
                  true
                )
              })
            ]
          }
        }
      ])
    },
    [
      _vm._v(" "),
      _vm._v(" "),
      _vm._v(" "),
      _c(
        "template",
        { slot: "actions" },
        [
          _c("el-table-column", {
            attrs: {
              label: _vm.$page.props.layoutLang.common.options,
              align: "center",
              "header-align": "center",
              width: "150"
            },
            scopedSlots: _vm._u([
              {
                key: "default",
                fn: function(ref) {
                  var row = ref.row
                  var column = ref.column
                  var $index = ref.$index
                  return [
                    _c(
                      "el-dropdown",
                      { attrs: { size: "small", trigger: "click" } },
                      [
                        _c(
                          "span",
                          { attrs: { type: "primary el-dropdown-link" } },
                          [
                            _vm._v(
                              "\n                        " +
                                _vm._s(
                                  _vm.$page.props.layoutLang.common.options
                                )
                            ),
                            _c("i", {
                              staticClass: "el-icon-arrow-down el-icon--right"
                            })
                          ]
                        ),
                        _vm._v(" "),
                        _c(
                          "el-dropdown-menu",
                          { attrs: { slot: "dropdown" }, slot: "dropdown" },
                          [
                            _c(
                              "el-dropdown-item",
                              [
                                _c(
                                  "inertia-link",
                                  {
                                    attrs: {
                                      href: _vm.$appRoute(
                                        "next-routes.web.items.show",
                                        row.id
                                      )
                                    }
                                  },
                                  [
                                    _vm._v(
                                      "\n                                " +
                                        _vm._s(
                                          _vm.$page.props.layoutLang.common.show
                                        ) +
                                        "\n                            "
                                    )
                                  ]
                                )
                              ],
                              1
                            ),
                            _vm._v(" "),
                            _c(
                              "el-dropdown-item",
                              [
                                _c(
                                  "inertia-link",
                                  {
                                    attrs: {
                                      href: _vm.$appRoute(
                                        "next-routes.web.items.edit",
                                        row.id
                                      )
                                    }
                                  },
                                  [
                                    _vm._v(
                                      "\n                                " +
                                        _vm._s(
                                          _vm.$page.props.layoutLang.common.edit
                                        ) +
                                        "\n                            "
                                    )
                                  ]
                                )
                              ],
                              1
                            ),
                            _vm._v(" "),
                            _c(
                              "el-dropdown-item",
                              [
                                _c(
                                  "inertia-link",
                                  {
                                    attrs: {
                                      href: _vm.$appRoute(
                                        "next-routes.web.items.show",
                                        row.id
                                      )
                                    }
                                  },
                                  [
                                    _vm._v(
                                      "\n                                " +
                                        _vm._s(
                                          _vm.$page.props.layoutLang.common
                                            .details
                                        ) +
                                        "\n                            "
                                    )
                                  ]
                                )
                              ],
                              1
                            ),
                            _vm._v(" "),
                            _c(
                              "el-dropdown-item",
                              [
                                _c(
                                  "inertia-link",
                                  {
                                    attrs: {
                                      href: _vm.$appRoute(
                                        "next-routes.web.items.history",
                                        row.id
                                      )
                                    }
                                  },
                                  [
                                    _vm._v(
                                      "\n                                " +
                                        _vm._s(_vm.$getLang("history")) +
                                        "\n                            "
                                    )
                                  ]
                                )
                              ],
                              1
                            ),
                            _vm._v(" "),
                            _c(
                              "el-dropdown-item",
                              [
                                _c(
                                  "inertia-link",
                                  {
                                    attrs: {
                                      href: _vm.$appRoute(
                                        "next-routes.web.items.serials",
                                        row.id
                                      )
                                    }
                                  },
                                  [
                                    _vm._v(
                                      "\n                                " +
                                        _vm._s(_vm.$getLang("serials")) +
                                        "\n                            "
                                    )
                                  ]
                                )
                              ],
                              1
                            )
                          ],
                          1
                        )
                      ],
                      1
                    ),
                    _vm._v(" "),
                    _c("div", { staticClass: "datagrid__actions-container" })
                  ]
                }
              }
            ])
          })
        ],
        1
      )
    ],
    2
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/next/js/Web/Pages/Daily/IndexPage.vue":
/*!*********************************************************!*\
  !*** ./resources/next/js/Web/Pages/Daily/IndexPage.vue ***!
  \*********************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _IndexPage_vue_vue_type_template_id_335f9313_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./IndexPage.vue?vue&type=template&id=335f9313&scoped=true& */ "./resources/next/js/Web/Pages/Daily/IndexPage.vue?vue&type=template&id=335f9313&scoped=true&");
/* harmony import */ var _IndexPage_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./IndexPage.vue?vue&type=script&lang=js& */ "./resources/next/js/Web/Pages/Daily/IndexPage.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _IndexPage_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _IndexPage_vue_vue_type_template_id_335f9313_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"],
  _IndexPage_vue_vue_type_template_id_335f9313_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  "335f9313",
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/next/js/Web/Pages/Daily/IndexPage.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/next/js/Web/Pages/Daily/IndexPage.vue?vue&type=script&lang=js&":
/*!**********************************************************************************!*\
  !*** ./resources/next/js/Web/Pages/Daily/IndexPage.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_IndexPage_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./IndexPage.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/next/js/Web/Pages/Daily/IndexPage.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_IndexPage_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/next/js/Web/Pages/Daily/IndexPage.vue?vue&type=template&id=335f9313&scoped=true&":
/*!****************************************************************************************************!*\
  !*** ./resources/next/js/Web/Pages/Daily/IndexPage.vue?vue&type=template&id=335f9313&scoped=true& ***!
  \****************************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_IndexPage_vue_vue_type_template_id_335f9313_scoped_true___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./IndexPage.vue?vue&type=template&id=335f9313&scoped=true& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/next/js/Web/Pages/Daily/IndexPage.vue?vue&type=template&id=335f9313&scoped=true&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_IndexPage_vue_vue_type_template_id_335f9313_scoped_true___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_IndexPage_vue_vue_type_template_id_335f9313_scoped_true___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);