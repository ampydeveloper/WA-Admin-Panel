(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[19],{

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/layout/AppBar.vue?vue&type=template&id=28373698&":
/*!**************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/frontend/layout/AppBar.vue?vue&type=template&id=28373698& ***!
  \**************************************************************************************************************************************************************************************************************/
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
    "v-app-bar",
    {
      staticClass: "custom-toolbar",
      attrs: {
        id: "app-bar",
        absolute: "",
        app: "",
        color: "transparent",
        flat: "",
        height: "75"
      }
    },
    [
      _c(
        "v-btn",
        { staticClass: "mr-3", attrs: { elevation: "1", fab: "", small: "" } },
        [
          _vm.value
            ? _c("v-icon", [_vm._v("mdi-view-quilt")])
            : _c("v-icon", [_vm._v("mdi-dots-vertical")])
        ],
        1
      ),
      _vm._v(" "),
      _c(
        "v-row",
        { staticClass: "user-image" },
        [
          _c(
            "v-col",
            {
              staticClass: "user-image-inner",
              attrs: { cols: "12", md: "12" }
            },
            [
              _c(
                "v-row",
                {
                  staticClass: "float-right",
                  attrs: { justify: "space-around" }
                },
                [
                  _c(
                    "v-menu",
                    {
                      attrs: {
                        bottom: "",
                        origin: "center center",
                        transition: "scale-transition"
                      }
                    },
                    [
                      _c(
                        "v-list",
                        { staticClass: "header-right-menu" },
                        [
                          _c(
                            "v-list-item",
                            [
                              _c("v-list-item-title", [
                                _vm._v(
                                  "\n                  Profile\n              "
                                )
                              ]),
                              _vm._v(" "),
                              _c("v-list-item-title", [
                                _vm._v(
                                  "\n                  User Management\n              "
                                )
                              ])
                            ],
                            1
                          )
                        ],
                        1
                      )
                    ],
                    1
                  )
                ],
                1
              )
            ],
            1
          )
        ],
        1
      )
    ],
    1
  )
}
var staticRenderFns = []
render._withStripped = true



/***/ }),

/***/ "./resources/js/frontend/layout/AppBar.vue":
/*!*************************************************!*\
  !*** ./resources/js/frontend/layout/AppBar.vue ***!
  \*************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _AppBar_vue_vue_type_template_id_28373698___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./AppBar.vue?vue&type=template&id=28373698& */ "./resources/js/frontend/layout/AppBar.vue?vue&type=template&id=28373698&");
/* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");

var script = {}


/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_1__["default"])(
  script,
  _AppBar_vue_vue_type_template_id_28373698___WEBPACK_IMPORTED_MODULE_0__["render"],
  _AppBar_vue_vue_type_template_id_28373698___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/frontend/layout/AppBar.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/frontend/layout/AppBar.vue?vue&type=template&id=28373698&":
/*!********************************************************************************!*\
  !*** ./resources/js/frontend/layout/AppBar.vue?vue&type=template&id=28373698& ***!
  \********************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AppBar_vue_vue_type_template_id_28373698___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./AppBar.vue?vue&type=template&id=28373698& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/frontend/layout/AppBar.vue?vue&type=template&id=28373698&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AppBar_vue_vue_type_template_id_28373698___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AppBar_vue_vue_type_template_id_28373698___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);