(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[12],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/admin/jobs/tab/OpenJobs.vue?vue&type=script&lang=js&":
/*!**********************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/admin/jobs/tab/OpenJobs.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _helpers_router__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../../../_helpers/router */ "./resources/js/_helpers/router.js");
/* harmony import */ var _services_job_service__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../../../_services/job.service */ "./resources/js/_services/job.service.js");
/* harmony import */ var _config_test_env__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../config/test.env */ "./resources/js/config/test.env.js");
/* harmony import */ var vue_feather_icons__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! vue-feather-icons */ "./node_modules/vue-feather-icons/dist/vue-feather-icons.es.js");
/* harmony import */ var _services_authentication_service__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! ../../../../_services/authentication.service */ "./resources/js/_services/authentication.service.js");
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





/* harmony default export */ __webpack_exports__["default"] = ({
  components: {
    PlusCircleIcon: vue_feather_icons__WEBPACK_IMPORTED_MODULE_3__["PlusCircleIcon"]
  },
  data: function data() {
    return {
      alljobs: "",
      isAdmin: true
    };
  },
  created: function created() {},
  mounted: function mounted() {
    var currentUser = _services_authentication_service__WEBPACK_IMPORTED_MODULE_4__["authenticationService"].currentUserValue;

    if (currentUser.data.user.role_id == 1) {
      this.isAdmin = true;
    } else {
      this.isAdmin = false;
    }

    this.getResults();
  },
  methods: {
    getResults: function getResults() {
      var _this = this;

      _services_job_service__WEBPACK_IMPORTED_MODULE_1__["jobService"].jobopned().then(function (response) {
        //handle response
        if (response.status) {
          _this.alljobs = response.data;
        } else {
          _this.$toast.open({
            message: response.message,
            type: "error",
            position: "top-right"
          });
        }
      });
    }
  },
  updated: function updated() {
    setTimeout(function () {
      $(document).ready(function () {
        $("#opned").DataTable();
      });
    }, 1000);
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/admin/jobs/tab/OpenJobs.vue?vue&type=template&id=17a0d110&":
/*!**************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/admin/jobs/tab/OpenJobs.vue?vue&type=template&id=17a0d110& ***!
  \**************************************************************************************************************************************************************************************************************************/
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
    "v-container",
    {
      staticClass: "pt-0",
      attrs: { id: "dashboard", fluid: "", tag: "section" }
    },
    [
      _c(
        "v-row",
        [
          _c("v-col", { attrs: { sm: "12", cols: "12" } }, [
            _c(
              "table",
              {
                staticClass: "table table-striped table-bordered",
                staticStyle: { width: "100%" },
                attrs: { id: "opned" }
              },
              [
                _c("thead", [
                  _c("tr", [
                    _c("th", [_vm._v("Sno")]),
                    _vm._v(" "),
                    _c("th", [_vm._v("Job Summary")]),
                    _vm._v(" "),
                    _c("th", [_vm._v("Sort By")]),
                    _vm._v(" "),
                    _c("th", [_vm._v("Payment")]),
                    _vm._v(" "),
                    _c("th", [_vm._v("Chat")])
                  ])
                ]),
                _vm._v(" "),
                _c(
                  "tbody",
                  _vm._l(_vm.alljobs, function(job, index) {
                    return _c("tr", [
                      _c("td", [_vm._v(_vm._s(index + 1))]),
                      _vm._v(" "),
                      _c("td", [
                        _vm._v(
                          "\n              " +
                            _vm._s(job.start_date) +
                            "\n              "
                        ),
                        _c("br"),
                        _vm._v(
                          "\n              " +
                            _vm._s(job.id) +
                            "\n              "
                        ),
                        _c("br"),
                        _vm._v(
                          "\n              $" +
                            _vm._s(job.job_amount) +
                            "\n              "
                        ),
                        _c("br"),
                        _vm._v(
                          "\n              " +
                            _vm._s(job.service.service_name) +
                            "\n            "
                        )
                      ]),
                      _vm._v(" "),
                      _c("td", [
                        _vm._v(
                          "\n              " +
                            _vm._s(job.customer.first_name) +
                            "\n              "
                        ),
                        _c("br"),
                        _vm._v(
                          "\n              " +
                            _vm._s(job.manager.first_name) +
                            "\n              "
                        ),
                        _c("br"),
                        _vm._v(
                          "\n              " +
                            _vm._s(job.manager.phone) +
                            "\n              "
                        ),
                        _c("br"),
                        _vm._v(
                          "\n              " +
                            _vm._s(job.manager.email) +
                            "\n              "
                        ),
                        _c("br"),
                        _vm._v(
                          "\n              " +
                            _vm._s(job.manager.address) +
                            " " +
                            _vm._s(job.manager.city) +
                            " " +
                            _vm._s(job.manager.state) +
                            " " +
                            _vm._s(job.manager.country) +
                            " " +
                            _vm._s(job.manager.zip_code) +
                            "\n            "
                        )
                      ]),
                      _vm._v(" "),
                      _c(
                        "td",
                        [
                          job.payment_status ? [_vm._v("Paid")] : _vm._e(),
                          _vm._v(" "),
                          !job.payment_status ? [_vm._v("Unpaid")] : _vm._e()
                        ],
                        2
                      ),
                      _vm._v(" "),
                      _c(
                        "td",
                        [
                          _vm.isAdmin
                            ? _c(
                                "router-link",
                                {
                                  staticClass: "nav-item nav-link",
                                  attrs: { to: "/admin/jobs/chat/" + job.id }
                                },
                                [_vm._v("View chat")]
                              )
                            : _vm._e(),
                          _vm._v(" "),
                          !_vm.isAdmin
                            ? _c(
                                "router-link",
                                {
                                  staticClass: "nav-item nav-link",
                                  attrs: { to: "/manager/jobs/chat/" + job.id }
                                },
                                [_vm._v("View chat")]
                              )
                            : _vm._e()
                        ],
                        1
                      )
                    ])
                  }),
                  0
                )
              ]
            )
          ])
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

/***/ "./resources/js/components/admin/jobs/tab/OpenJobs.vue":
/*!*************************************************************!*\
  !*** ./resources/js/components/admin/jobs/tab/OpenJobs.vue ***!
  \*************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _OpenJobs_vue_vue_type_template_id_17a0d110___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./OpenJobs.vue?vue&type=template&id=17a0d110& */ "./resources/js/components/admin/jobs/tab/OpenJobs.vue?vue&type=template&id=17a0d110&");
/* harmony import */ var _OpenJobs_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./OpenJobs.vue?vue&type=script&lang=js& */ "./resources/js/components/admin/jobs/tab/OpenJobs.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _OpenJobs_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _OpenJobs_vue_vue_type_template_id_17a0d110___WEBPACK_IMPORTED_MODULE_0__["render"],
  _OpenJobs_vue_vue_type_template_id_17a0d110___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/admin/jobs/tab/OpenJobs.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/admin/jobs/tab/OpenJobs.vue?vue&type=script&lang=js&":
/*!**************************************************************************************!*\
  !*** ./resources/js/components/admin/jobs/tab/OpenJobs.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_OpenJobs_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./OpenJobs.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/admin/jobs/tab/OpenJobs.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_OpenJobs_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/admin/jobs/tab/OpenJobs.vue?vue&type=template&id=17a0d110&":
/*!********************************************************************************************!*\
  !*** ./resources/js/components/admin/jobs/tab/OpenJobs.vue?vue&type=template&id=17a0d110& ***!
  \********************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_OpenJobs_vue_vue_type_template_id_17a0d110___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./OpenJobs.vue?vue&type=template&id=17a0d110& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/admin/jobs/tab/OpenJobs.vue?vue&type=template&id=17a0d110&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_OpenJobs_vue_vue_type_template_id_17a0d110___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_OpenJobs_vue_vue_type_template_id_17a0d110___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);