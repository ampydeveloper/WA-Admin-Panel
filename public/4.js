(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[4],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/admin/customer/tab/info.vue?vue&type=script&lang=js&":
/*!**********************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/admin/customer/tab/info.vue?vue&type=script&lang=js& ***!
  \**********************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var vuelidate_lib_validators__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! vuelidate/lib/validators */ "./node_modules/vuelidate/lib/validators/index.js");
/* harmony import */ var vuelidate_lib_validators__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(vuelidate_lib_validators__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var _services_customer_service__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../../../_services/customer.service */ "./resources/js/_services/customer.service.js");
/* harmony import */ var _helpers_router__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../_helpers/router */ "./resources/js/_helpers/router.js");
/* harmony import */ var _config_test_env__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ../../../../config/test.env */ "./resources/js/config/test.env.js");
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




/* harmony default export */ __webpack_exports__["default"] = ({
  data: function data() {
    return {
      prefixs: ['Ms.', 'Mr.', 'Mrs.'],
      isLoading: false,
      items: [],
      model: null,
      valid: true,
      avatar: null,
      menu2: false,
      menu1: false,
      date: "",
      date1: "",
      customer_img: "",
      manager_img: "",
      apiUrl: _config_test_env__WEBPACK_IMPORTED_MODULE_3__["environment"].apiUrl,
      addForm: {
        prefix: "",
        customer_name: "",
        email: "",
        phone: "",
        address: "",
        city: "",
        province: "",
        user_image: null,
        zipcode: '',
        is_active: true
      },
      emailRules: [function (v) {
        return !!v || "E-mail is required";
      }, function (v) {
        return /.+@.+/.test(v) || "E-mail must be valid";
      }],
      phoneRules: [function (v) {
        return !!v || "Phone number is required";
      }, function (v) {
        return /^\d*$/.test(v) || "Enter valid number";
      }, function (v) {
        return v.length >= 10 || "Enter valid number length";
      }],
      rules: [function (value) {
        return !value || value.size < 2000000 || "Avatar size should be less than 2 MB!";
      }],
      myFiles: []
    };
  },
  mounted: function mounted() {},
  computed: {
    serverOptions: function serverOptions() {
      var currentUser = JSON.parse(localStorage.getItem("currentUser"));
      return {
        url: this.apiUrl,
        withCredentials: false,
        process: {
          url: "uploadImage",
          headers: {
            Authorization: "Bearer " + currentUser.data.access_token
          }
        }
      };
    },
    url: function url() {
      if (this.file) {
        var parsedUrl = new URL(this.file);
        return [parsedUrl.pathname];
      } else {
        return null;
      }
    }
  },
  created: function created() {
    this.customer_img = "/images/avatar.png";
    this.manager_image = "/images/avatar.png";
  },
  methods: {
    getAddressData: function getAddressData(addressData, placeResultData, id) {
      console.log(addressData.route);
      this.addForm.latitude = addressData.latitude;
      this.addForm.longitude = addressData.longitude;
      this.addForm.farm_address = addressData.route;
    },
    handleProcessFile: function handleProcessFile(error, file) {
      this.customer_img = "../../" + file.serverId;
      this.addForm.user_image = file.serverId;
    },
    //farm images process
    handleProcessFile1: function handleProcessFile1(error, file) {
      this.addForm.farm_images.push(file.serverId);
    },
    //manager image process
    handleProcessFile2: function handleProcessFile2(error, file) {
      this.manager_img = "../../" + file.serverId;
      this.addForm.manager_image = file.serverId;
    },
    //manager id card image process
    handleProcessFile3: function handleProcessFile3(error, file) {
      this.addForm.manager_card_image = file.serverId; //this.docError = false;
    },
    update: function update() {
      var _this = this;

      console.log(this.addForm);

      if (this.$refs.form.validate()) {
        _services_customer_service__WEBPACK_IMPORTED_MODULE_1__["customerService"].add(this.addForm).then(function (response) {
          //handle response
          if (response.status) {
            _this.$toast.open({
              message: response.message,
              type: "success",
              position: "top-right"
            }); //redirect to login


            _helpers_router__WEBPACK_IMPORTED_MODULE_2__["router"].push("/admin/customer");
          } else {
            _this.$toast.open({
              message: response.message,
              type: "error",
              position: "top-right"
            });
          }
        });
      }
    }
  }
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/admin/customer/tab/info.vue?vue&type=template&id=6afefa3e&":
/*!**************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/admin/customer/tab/info.vue?vue&type=template&id=6afefa3e& ***!
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
    "v-app",
    [
      _c(
        "v-container",
        [
          _c(
            "v-row",
            [
              _c(
                "v-col",
                { attrs: { cols: "12", md: "12" } },
                [
                  _c(
                    "v-form",
                    {
                      ref: "form",
                      attrs: { "lazy-validation": "" },
                      model: {
                        value: _vm.valid,
                        callback: function($$v) {
                          _vm.valid = $$v
                        },
                        expression: "valid"
                      }
                    },
                    [
                      _c(
                        "v-row",
                        [
                          _c(
                            "v-col",
                            { attrs: { cols: "12", md: "12" } },
                            [
                              _c(
                                "v-row",
                                [
                                  _c(
                                    "v-col",
                                    { attrs: { cols: "12", md: "12" } },
                                    [
                                      _c(
                                        "div",
                                        {
                                          staticClass:
                                            "v-avatar v-list-item__avatar",
                                          staticStyle: {
                                            height: "40px",
                                            "min-width": "40px",
                                            width: "40px"
                                          }
                                        },
                                        [
                                          _c("img", {
                                            attrs: { src: _vm.customer_img }
                                          })
                                        ]
                                      ),
                                      _vm._v(" "),
                                      _c("file-pond", {
                                        ref: "pond",
                                        attrs: {
                                          name: "uploadImage",
                                          "label-idle": "Add Profile pic...",
                                          "allow-multiple": "false",
                                          server: _vm.serverOptions,
                                          files: _vm.myFiles,
                                          "allow-file-type-validation": "true",
                                          "accepted-file-types":
                                            "image/jpeg, image/png"
                                        },
                                        on: {
                                          processfile: _vm.handleProcessFile
                                        }
                                      })
                                    ],
                                    1
                                  ),
                                  _vm._v(" "),
                                  _c(
                                    "v-col",
                                    { attrs: { cols: "3", md: "3" } },
                                    [
                                      _c("v-select", {
                                        attrs: {
                                          items: _vm.prefixs,
                                          label: "Prefix",
                                          rules: [
                                            function(v) {
                                              return !!v || "Prefix is required"
                                            }
                                          ],
                                          dense: ""
                                        },
                                        model: {
                                          value: _vm.addForm.prefix,
                                          callback: function($$v) {
                                            _vm.$set(_vm.addForm, "prefix", $$v)
                                          },
                                          expression: "addForm.prefix"
                                        }
                                      })
                                    ],
                                    1
                                  ),
                                  _vm._v(" "),
                                  _c(
                                    "v-col",
                                    { attrs: { cols: "3", md: "3" } },
                                    [
                                      _c("v-text-field", {
                                        attrs: {
                                          label: "Name",
                                          required: "",
                                          rules: [
                                            function(v) {
                                              return (
                                                !!v ||
                                                "Customer name is required"
                                              )
                                            }
                                          ]
                                        },
                                        model: {
                                          value: _vm.addForm.customer_name,
                                          callback: function($$v) {
                                            _vm.$set(
                                              _vm.addForm,
                                              "customer_name",
                                              $$v
                                            )
                                          },
                                          expression: "addForm.customer_name"
                                        }
                                      })
                                    ],
                                    1
                                  ),
                                  _vm._v(" "),
                                  _c(
                                    "v-col",
                                    { attrs: { cols: "3", md: "3" } },
                                    [
                                      _c("v-text-field", {
                                        attrs: {
                                          rules: _vm.emailRules,
                                          name: "email",
                                          label: "E-mail",
                                          required: ""
                                        },
                                        model: {
                                          value: _vm.addForm.email,
                                          callback: function($$v) {
                                            _vm.$set(_vm.addForm, "email", $$v)
                                          },
                                          expression: "addForm.email"
                                        }
                                      })
                                    ],
                                    1
                                  ),
                                  _vm._v(" "),
                                  _c(
                                    "v-col",
                                    { attrs: { cols: "3", md: "3" } },
                                    [
                                      _c("v-text-field", {
                                        attrs: {
                                          rules: _vm.phoneRules,
                                          label: "Phone",
                                          required: "",
                                          maxlength: "10"
                                        },
                                        model: {
                                          value: _vm.addForm.phone,
                                          callback: function($$v) {
                                            _vm.$set(_vm.addForm, "phone", $$v)
                                          },
                                          expression: "addForm.phone"
                                        }
                                      })
                                    ],
                                    1
                                  ),
                                  _vm._v(" "),
                                  _c(
                                    "v-col",
                                    { attrs: { cols: "3", md: "3" } },
                                    [
                                      _c("v-text-field", {
                                        attrs: {
                                          label: "Address",
                                          required: "",
                                          rules: [
                                            function(v) {
                                              return (
                                                !!v || "address is required"
                                              )
                                            }
                                          ]
                                        },
                                        model: {
                                          value: _vm.addForm.address,
                                          callback: function($$v) {
                                            _vm.$set(
                                              _vm.addForm,
                                              "address",
                                              $$v
                                            )
                                          },
                                          expression: "addForm.address"
                                        }
                                      })
                                    ],
                                    1
                                  ),
                                  _vm._v(" "),
                                  _c(
                                    "v-col",
                                    { attrs: { cols: "3", md: "3" } },
                                    [
                                      _c("v-text-field", {
                                        attrs: {
                                          label: "City",
                                          required: "",
                                          rules: [
                                            function(v) {
                                              return !!v || "City is required"
                                            }
                                          ]
                                        },
                                        model: {
                                          value: _vm.addForm.city,
                                          callback: function($$v) {
                                            _vm.$set(_vm.addForm, "city", $$v)
                                          },
                                          expression: "addForm.city"
                                        }
                                      })
                                    ],
                                    1
                                  ),
                                  _vm._v(" "),
                                  _c(
                                    "v-col",
                                    { attrs: { cols: "3", md: "3" } },
                                    [
                                      _c("v-text-field", {
                                        attrs: {
                                          label: "Province",
                                          required: "",
                                          rules: [
                                            function(v) {
                                              return (
                                                !!v || "Province is required"
                                              )
                                            }
                                          ]
                                        },
                                        model: {
                                          value: _vm.addForm.province,
                                          callback: function($$v) {
                                            _vm.$set(
                                              _vm.addForm,
                                              "province",
                                              $$v
                                            )
                                          },
                                          expression: "addForm.province"
                                        }
                                      })
                                    ],
                                    1
                                  ),
                                  _vm._v(" "),
                                  _c(
                                    "v-col",
                                    { attrs: { cols: "3", md: "3" } },
                                    [
                                      _c("v-text-field", {
                                        attrs: {
                                          rules: [
                                            function(v) {
                                              return (
                                                !!v || "Zip code is required"
                                              )
                                            }
                                          ],
                                          label: "zipcode",
                                          required: ""
                                        },
                                        model: {
                                          value: _vm.addForm.zipcode,
                                          callback: function($$v) {
                                            _vm.$set(
                                              _vm.addForm,
                                              "zipcode",
                                              $$v
                                            )
                                          },
                                          expression: "addForm.zipcode"
                                        }
                                      })
                                    ],
                                    1
                                  ),
                                  _vm._v(" "),
                                  _c(
                                    "v-col",
                                    { attrs: { cols: "3", md: "3" } },
                                    [
                                      _c("v-switch", {
                                        staticClass: "mx-2",
                                        attrs: { label: "Is Active" },
                                        model: {
                                          value: _vm.addForm.is_active,
                                          callback: function($$v) {
                                            _vm.$set(
                                              _vm.addForm,
                                              "is_active",
                                              $$v
                                            )
                                          },
                                          expression: "addForm.is_active"
                                        }
                                      })
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
                          _c(
                            "v-btn",
                            {
                              staticClass: "mr-4",
                              attrs: { color: "success" },
                              on: { click: _vm.update }
                            },
                            [_vm._v("Submit")]
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

/***/ "./resources/js/components/admin/customer/tab/info.vue":
/*!*************************************************************!*\
  !*** ./resources/js/components/admin/customer/tab/info.vue ***!
  \*************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _info_vue_vue_type_template_id_6afefa3e___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./info.vue?vue&type=template&id=6afefa3e& */ "./resources/js/components/admin/customer/tab/info.vue?vue&type=template&id=6afefa3e&");
/* harmony import */ var _info_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./info.vue?vue&type=script&lang=js& */ "./resources/js/components/admin/customer/tab/info.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _info_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _info_vue_vue_type_template_id_6afefa3e___WEBPACK_IMPORTED_MODULE_0__["render"],
  _info_vue_vue_type_template_id_6afefa3e___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/admin/customer/tab/info.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/admin/customer/tab/info.vue?vue&type=script&lang=js&":
/*!**************************************************************************************!*\
  !*** ./resources/js/components/admin/customer/tab/info.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_info_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./info.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/admin/customer/tab/info.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_info_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/admin/customer/tab/info.vue?vue&type=template&id=6afefa3e&":
/*!********************************************************************************************!*\
  !*** ./resources/js/components/admin/customer/tab/info.vue?vue&type=template&id=6afefa3e& ***!
  \********************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_info_vue_vue_type_template_id_6afefa3e___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./info.vue?vue&type=template&id=6afefa3e& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/admin/customer/tab/info.vue?vue&type=template&id=6afefa3e&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_info_vue_vue_type_template_id_6afefa3e___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_info_vue_vue_type_template_id_6afefa3e___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);