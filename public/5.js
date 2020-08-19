(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[5],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/admin/customer/tab/farm.vue?vue&type=script&lang=js&":
/*!**********************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/admin/customer/tab/farm.vue?vue&type=script&lang=js& ***!
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
/* harmony import */ var vue_google_autocomplete__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! vue-google-autocomplete */ "./node_modules/vue-google-autocomplete/src/VueGoogleAutocomplete.vue");
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
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
    VueGoogleAutocomplete: vue_google_autocomplete__WEBPACK_IMPORTED_MODULE_4__["default"]
  },
  data: function data() {
    return {
      loading: null,
      docError: false,
      editSwitch: false,
      search: null,
      isOpen: false,
      prefixs: ["Ms.", "Mr.", "Mrs."],
      isLoading: false,
      uploadIndex: null,
      uploadChildIndex: null,
      items: [],
      model: null,
      valid: true,
      manager_img: "",
      apiUrl: _config_test_env__WEBPACK_IMPORTED_MODULE_3__["environment"].apiUrl,
      uberMapApiUrl: _config_test_env__WEBPACK_IMPORTED_MODULE_3__["environment"].uberMapApiUrl,
      uberMapToken: _config_test_env__WEBPACK_IMPORTED_MODULE_3__["environment"].uberMapToken,
      farmAndManagerForm: {
        farm: '',
        managers: Array()
      },
      totalForm: Array(),
      formDisable: Array(),
      addForm: {
        farm_id: "",
        farm_images: [],
        latitude: "",
        longitude: "",
        farm_address: "",
        farm_unit: "",
        farm_city: "",
        farm_province: "",
        farm_zipcode: "",
        farm_active: true
      },
      managerForm: {
        manager_id: "",
        manager_image: "",
        manager_prefix: "",
        manager_name: "",
        manager_email: "",
        manager_phone: "",
        manager_address: "",
        manager_city: "",
        manager_province: "",
        manager_zipcode: "",
        manager_id_card: "",
        manager_card_image: ""
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
  mounted: function mounted() {
    var _this = this;

    // this.$refs.address.focus();
    _services_customer_service__WEBPACK_IMPORTED_MODULE_1__["customerService"].getFarmAndManager(this.$route.params.id).then(function (response) {
      //handle response
      if (response.status) {
        for (var total = 0; total < response.data.length; total++) {
          //make blank initially
          _this.farmAndManagerForm = {
            farm: '',
            managers: []
          };
          var farmDetails = response.data[total];
          var managerDetails = response.data[total].farm_manager; //set farm values

          _this.addForm = {
            farm_id: farmDetails.id,
            farm_images: JSON.parse(farmDetails.farm_image),
            latitude: farmDetails.latitude,
            longitude: farmDetails.longitude,
            farm_address: farmDetails.farm_address,
            farm_unit: farmDetails.farm_unit,
            farm_city: farmDetails.farm_city,
            farm_province: farmDetails.farm_province,
            farm_zipcode: farmDetails.farm_zipcode,
            farm_active: farmDetails.farm_active == 1 ? true : false
          };
          _this.farmAndManagerForm.farm = _this.addForm;

          for (var fm = 0; fm < managerDetails.length; fm++) {
            _this.managerForm = {
              manager_id: managerDetails[fm].id,
              manager_image: managerDetails[fm].user_image,
              manager_prefix: managerDetails[fm].prefix,
              manager_name: managerDetails[fm].first_name,
              manager_email: managerDetails[fm].email,
              manager_phone: managerDetails[fm].phone,
              manager_address: managerDetails[fm].address,
              manager_city: managerDetails[fm].city,
              manager_province: managerDetails[fm].state,
              manager_zipcode: managerDetails[fm].zip_code,
              manager_id_card: managerDetails[fm].manager.identification_number,
              manager_card_image: managerDetails[fm].manager.document
            }; //add to manager info

            _this.farmAndManagerForm.managers.push(_this.managerForm);
          } //add into total forms


          _this.totalForm.push(_this.farmAndManagerForm); //add into disabled forms


          _this.formDisable.push(total);
        }
      } else {
        _this.$toast.open({
          message: response.message,
          type: "error",
          position: "top-right"
        });
      }
    });
  },
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
    this.manager_image = "/images/avatar.png";
  },
  methods: {
    onChange: function onChange(val) {
      var _this2 = this;

      this.items = ""; // Items have already been loaded

      if (this.items.length > 1) return false;
      this.isLoading = true; // Lazily load input items

      axios.get(this.uberMapApiUrl + val + ".json?access_token=" + this.uberMapToken).then(function (response) {
        _this2.items = response.data.features;
        _this2.isLoading = false;
        _this2.isOpen = true;
      });
    },
    setResult: function setResult(result, index) {
      this.search = result.text;
      this.totalForm[index].latitude = result.center[1];
      this.totalForm[index].longitude = result.center[0];
      this.totalForm[index].farm_address = result.text;
      this.isOpen = false;
    },
    setUploadIndex: function setUploadIndex(parentIndex) {
      var childIndex = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;
      this.uploadIndex = parentIndex;
      this.uploadChildIndex = childIndex;
    },
    //farm images process
    handleProcessFile1: function handleProcessFile1(error, file) {
      this.totalForm[this.uploadIndex].farm.farm_images.push(file.serverId); //set default as null

      this.uploadIndex = null;
      this.uploadChildIndex = null;
    },
    //farm image remove process
    handleRemoveFile1: function handleRemoveFile1(parentIndex) {},
    //manager image process
    handleProcessFile2: function handleProcessFile2(error, file) {
      this.totalForm[this.uploadIndex].managers[this.uploadChildIndex].manager_image = file.serverId; //set default as null

      this.uploadIndex = null;
      this.uploadChildIndex = null;
    },
    //manager image process
    handleRemoveFile2: function handleRemoveFile2(parentIndex, childIndex) {
      this.totalForm[parentIndex].managers[childIndex].manager_image = '';
      this.totalForm[parentIndex].managers[childIndex].manager_image = 'images/avatar.png';
    },
    //manager id card image process
    handleProcessFile3: function handleProcessFile3(error, file) {
      this.totalForm[this.uploadIndex].managers[this.uploadChildIndex].manager_card_image = file.serverId; //set default as null

      this.uploadIndex = null;
      this.uploadChildIndex = null;
    },
    //manager id card image removal process
    handleRemoveFile3: function handleRemoveFile3(parentIndex, childIndex) {
      this.totalForm[parentIndex].managers[childIndex].manager_card_image = '';
    },
    enableForm: function enableForm(formId) {
      var index = this.formDisable.indexOf(formId);

      if (index > -1) {
        //remove if found
        this.formDisable.splice(index, 1);
      } else {
        //add into array
        this.formDisable.push(formId);
      }
    },
    update: function update(formId) {
      var _this3 = this;

      //validate if image uploading is in-progress
      if (this.uploadIndex != null) {
        this.$toast.open({
          message: "Image uploading is in progress!",
          type: "error",
          position: "top-right"
        });
        return false;
      } //validate if image uploading is in-progress


      if (this.$refs.form[formId].validate()) {
        //start loading
        this.loading = formId;
        _services_customer_service__WEBPACK_IMPORTED_MODULE_1__["customerService"].updateFarmManager(this.totalForm[formId]).then(function (response) {
          //stop loading
          _this3.loading = null; //handle response

          if (response.status) {
            _this3.$toast.open({
              message: response.message,
              type: "success",
              position: "top-right"
            });
          } else {
            _this3.$toast.open({
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

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/admin/customer/tab/farm.vue?vue&type=template&id=cfef2c74&":
/*!**************************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/admin/customer/tab/farm.vue?vue&type=template&id=cfef2c74& ***!
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
        { attrs: { fluid: "" } },
        [
          _c(
            "v-row",
            _vm._l(_vm.totalForm, function(updateForm, parentIndex) {
              return _c(
                "div",
                [
                  _c(
                    "v-col",
                    { attrs: { cols: "12", md: "12" } },
                    [
                      _c(
                        "v-form",
                        {
                          ref: "form",
                          refInFor: true,
                          staticClass: "customer-form",
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
                                  _c("h4", { staticClass: "main-title" }, [
                                    _vm._v("Farm Section")
                                  ]),
                                  _vm._v(" "),
                                  _c(
                                    "v-row",
                                    [
                                      _c(
                                        "v-col",
                                        { attrs: { cols: "12", md: "12" } },
                                        [
                                          _c("file-pond", {
                                            ref: "pond",
                                            refInFor: true,
                                            attrs: {
                                              name: "uploadImage",
                                              "label-idle": "Farm Images",
                                              "allow-multiple": "true",
                                              server: _vm.serverOptions,
                                              files: _vm.myFiles,
                                              "allow-file-type-validation":
                                                "true",
                                              "accepted-file-types":
                                                "image/jpeg, image/png",
                                              disabled: _vm.formDisable.includes(
                                                parentIndex
                                              )
                                                ? true
                                                : false
                                            },
                                            on: {
                                              addfilestart: function($event) {
                                                return _vm.setUploadIndex(
                                                  parentIndex
                                                )
                                              },
                                              processfile:
                                                _vm.handleProcessFile1,
                                              processfilerevert: function(
                                                $event
                                              ) {
                                                return _vm.handleRemoveFile1(
                                                  parentIndex
                                                )
                                              }
                                            }
                                          })
                                        ],
                                        1
                                      ),
                                      _vm._v(" "),
                                      _c(
                                        "v-col",
                                        {
                                          staticClass: "mt-4",
                                          attrs: { cols: "3", md: "3" }
                                        },
                                        [
                                          _c("v-text-field", {
                                            staticClass: "mt-m11",
                                            attrs: {
                                              type: "text",
                                              label: "Search Place",
                                              required: "",
                                              rules: [
                                                function(v) {
                                                  return (
                                                    !!v || "Place is required"
                                                  )
                                                }
                                              ],
                                              disabled: _vm.formDisable.includes(
                                                parentIndex
                                              )
                                                ? true
                                                : false
                                            },
                                            on: {
                                              input: function($event) {
                                                return _vm.onChange(
                                                  updateForm.farm.farm_address
                                                )
                                              }
                                            },
                                            model: {
                                              value:
                                                updateForm.farm.farm_address,
                                              callback: function($$v) {
                                                _vm.$set(
                                                  updateForm.farm,
                                                  "farm_address",
                                                  $$v
                                                )
                                              },
                                              expression:
                                                "updateForm.farm.farm_address"
                                            }
                                          }),
                                          _vm._v(" "),
                                          _c(
                                            "ul",
                                            {
                                              directives: [
                                                {
                                                  name: "show",
                                                  rawName: "v-show",
                                                  value:
                                                    _vm.isOpen &&
                                                    !_vm.formDisable.includes(
                                                      parentIndex
                                                    ),
                                                  expression:
                                                    "isOpen && !formDisable.includes(parentIndex)"
                                                }
                                              ],
                                              staticClass:
                                                "autocomplete-results"
                                            },
                                            _vm._l(_vm.items, function(
                                              result,
                                              i
                                            ) {
                                              return _c(
                                                "li",
                                                {
                                                  key: i,
                                                  staticClass:
                                                    "autocomplete-result",
                                                  on: {
                                                    click: function($event) {
                                                      return _vm.setResult(
                                                        result,
                                                        parentIndex
                                                      )
                                                    }
                                                  }
                                                },
                                                [
                                                  _vm._v(
                                                    _vm._s(result.place_name)
                                                  )
                                                ]
                                              )
                                            }),
                                            0
                                          )
                                        ],
                                        1
                                      ),
                                      _vm._v(" "),
                                      _c(
                                        "v-col",
                                        { attrs: { cols: "3", md: "3" } },
                                        [
                                          _c(
                                            "v-col",
                                            {
                                              staticClass: "pl-0 pt-0 pb-0",
                                              attrs: { cols: "4", sm: "4" }
                                            },
                                            [
                                              _c(
                                                "label",
                                                { staticClass: "ft-normal" },
                                                [_vm._v("Apt/Unit")]
                                              )
                                            ]
                                          ),
                                          _vm._v(" "),
                                          _c(
                                            "v-col",
                                            {
                                              staticClass: "p-0 ml-m4",
                                              attrs: { cols: "8", sm: "8" }
                                            },
                                            [
                                              _c("v-text-field", {
                                                staticClass: "disabled-tag",
                                                attrs: {
                                                  required: "",
                                                  rules: [
                                                    function(v) {
                                                      return (
                                                        !!v ||
                                                        "Farm apt/unit is required"
                                                      )
                                                    }
                                                  ],
                                                  disabled: _vm.formDisable.includes(
                                                    parentIndex
                                                  )
                                                    ? true
                                                    : false
                                                },
                                                model: {
                                                  value:
                                                    updateForm.farm.farm_unit,
                                                  callback: function($$v) {
                                                    _vm.$set(
                                                      updateForm.farm,
                                                      "farm_unit",
                                                      $$v
                                                    )
                                                  },
                                                  expression:
                                                    "updateForm.farm.farm_unit"
                                                }
                                              })
                                            ],
                                            1
                                          )
                                        ],
                                        1
                                      ),
                                      _vm._v(" "),
                                      _c(
                                        "v-col",
                                        { attrs: { cols: "3", md: "3" } },
                                        [
                                          _c(
                                            "v-col",
                                            {
                                              staticClass: "pl-0 pt-0 pb-0",
                                              attrs: { cols: "4", sm: "4" }
                                            },
                                            [
                                              _c(
                                                "label",
                                                { staticClass: "ft-normal" },
                                                [_vm._v("City")]
                                              )
                                            ]
                                          ),
                                          _vm._v(" "),
                                          _c(
                                            "v-col",
                                            {
                                              staticClass: "p-0 ml-m4",
                                              attrs: { cols: "8", sm: "8" }
                                            },
                                            [
                                              _c("v-text-field", {
                                                staticClass: "disabled-tag",
                                                attrs: {
                                                  required: "",
                                                  rules: [
                                                    function(v) {
                                                      return (
                                                        !!v ||
                                                        "Farm city is required"
                                                      )
                                                    }
                                                  ],
                                                  disabled: _vm.formDisable.includes(
                                                    parentIndex
                                                  )
                                                    ? true
                                                    : false
                                                },
                                                model: {
                                                  value:
                                                    updateForm.farm.farm_city,
                                                  callback: function($$v) {
                                                    _vm.$set(
                                                      updateForm.farm,
                                                      "farm_city",
                                                      $$v
                                                    )
                                                  },
                                                  expression:
                                                    "updateForm.farm.farm_city"
                                                }
                                              })
                                            ],
                                            1
                                          )
                                        ],
                                        1
                                      ),
                                      _vm._v(" "),
                                      _c(
                                        "v-col",
                                        { attrs: { cols: "3", md: "3" } },
                                        [
                                          _c(
                                            "v-col",
                                            {
                                              staticClass: "pl-0 pt-0 pb-0",
                                              attrs: { cols: "4", sm: "4" }
                                            },
                                            [
                                              _c(
                                                "label",
                                                { staticClass: "ft-normal" },
                                                [_vm._v("Province")]
                                              )
                                            ]
                                          ),
                                          _vm._v(" "),
                                          _c(
                                            "v-col",
                                            {
                                              staticClass: "p-0 ml-m4",
                                              attrs: { cols: "8", sm: "8" }
                                            },
                                            [
                                              _c("v-text-field", {
                                                staticClass: "disabled-tag",
                                                attrs: {
                                                  required: "",
                                                  rules: [
                                                    function(v) {
                                                      return (
                                                        !!v ||
                                                        "Farm province is required"
                                                      )
                                                    }
                                                  ],
                                                  disabled: _vm.formDisable.includes(
                                                    parentIndex
                                                  )
                                                    ? true
                                                    : false
                                                },
                                                model: {
                                                  value:
                                                    updateForm.farm
                                                      .farm_province,
                                                  callback: function($$v) {
                                                    _vm.$set(
                                                      updateForm.farm,
                                                      "farm_province",
                                                      $$v
                                                    )
                                                  },
                                                  expression:
                                                    "updateForm.farm.farm_province"
                                                }
                                              })
                                            ],
                                            1
                                          )
                                        ],
                                        1
                                      ),
                                      _vm._v(" "),
                                      _c(
                                        "v-col",
                                        { attrs: { cols: "3", md: "3" } },
                                        [
                                          _c(
                                            "v-col",
                                            {
                                              staticClass: "pl-0 pt-0 pb-0",
                                              attrs: { cols: "4", sm: "4" }
                                            },
                                            [
                                              _c(
                                                "label",
                                                { staticClass: "ft-normal" },
                                                [_vm._v("Zip Code")]
                                              )
                                            ]
                                          ),
                                          _vm._v(" "),
                                          _c(
                                            "v-col",
                                            {
                                              staticClass: "p-0 ml-m4",
                                              attrs: { cols: "8", sm: "8" }
                                            },
                                            [
                                              _c("v-text-field", {
                                                staticClass: "disabled-tag",
                                                attrs: {
                                                  required: "",
                                                  rules: [
                                                    function(v) {
                                                      return (
                                                        !!v ||
                                                        "Farm zip code is required"
                                                      )
                                                    }
                                                  ],
                                                  disabled: _vm.formDisable.includes(
                                                    parentIndex
                                                  )
                                                    ? true
                                                    : false
                                                },
                                                model: {
                                                  value:
                                                    updateForm.farm
                                                      .farm_zipcode,
                                                  callback: function($$v) {
                                                    _vm.$set(
                                                      updateForm.farm,
                                                      "farm_zipcode",
                                                      $$v
                                                    )
                                                  },
                                                  expression:
                                                    "updateForm.farm.farm_zipcode"
                                                }
                                              })
                                            ],
                                            1
                                          )
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
                                              value:
                                                updateForm.farm.farm_active,
                                              callback: function($$v) {
                                                _vm.$set(
                                                  updateForm.farm,
                                                  "farm_active",
                                                  $$v
                                                )
                                              },
                                              expression:
                                                "updateForm.farm.farm_active"
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
                              _c("v-col", { attrs: { cols: "12", md: "12" } }, [
                                _c("h3", [_vm._v("Manager Details")])
                              ]),
                              _vm._v(" "),
                              _vm._l(updateForm.managers, function(
                                manager,
                                childIndex
                              ) {
                                return [
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
                                                    attrs: {
                                                      src:
                                                        "../../../" +
                                                        manager.manager_image
                                                    }
                                                  })
                                                ]
                                              ),
                                              _vm._v(" "),
                                              _c("file-pond", {
                                                ref: "pond",
                                                refInFor: true,
                                                attrs: {
                                                  name: "uploadImage",
                                                  "label-idle":
                                                    "Add Profile Pic",
                                                  "allow-multiple": "false",
                                                  server: _vm.serverOptions,
                                                  files: _vm.myFiles,
                                                  "allow-file-type-validation":
                                                    "true",
                                                  "accepted-file-types":
                                                    "image/jpeg, image/png",
                                                  disabled: _vm.formDisable.includes(
                                                    parentIndex
                                                  )
                                                    ? true
                                                    : false
                                                },
                                                on: {
                                                  addfilestart: function(
                                                    $event
                                                  ) {
                                                    return _vm.setUploadIndex(
                                                      parentIndex,
                                                      childIndex
                                                    )
                                                  },
                                                  processfile:
                                                    _vm.handleProcessFile2,
                                                  processfilerevert: function(
                                                    $event
                                                  ) {
                                                    return _vm.handleRemoveFile2(
                                                      parentIndex,
                                                      childIndex
                                                    )
                                                  }
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
                                              _c(
                                                "v-col",
                                                {
                                                  staticClass: "pl-0 pt-0 pb-0",
                                                  attrs: { cols: "4", sm: "4" }
                                                },
                                                [
                                                  _c(
                                                    "label",
                                                    {
                                                      staticClass: "ft-normal"
                                                    },
                                                    [_vm._v("Prefix")]
                                                  )
                                                ]
                                              ),
                                              _vm._v(" "),
                                              _c(
                                                "v-col",
                                                {
                                                  staticClass: "p-0 ml-m4",
                                                  attrs: { cols: "8", sm: "8" }
                                                },
                                                [
                                                  _c("v-select", {
                                                    staticClass: "disabled-tag",
                                                    attrs: {
                                                      items: _vm.prefixs,
                                                      label: "Select",
                                                      rules: [
                                                        function(v) {
                                                          return (
                                                            !!v ||
                                                            "Prefix is required"
                                                          )
                                                        }
                                                      ],
                                                      disabled: _vm.formDisable.includes(
                                                        parentIndex
                                                      )
                                                        ? true
                                                        : false
                                                    },
                                                    model: {
                                                      value:
                                                        manager.manager_prefix,
                                                      callback: function($$v) {
                                                        _vm.$set(
                                                          manager,
                                                          "manager_prefix",
                                                          $$v
                                                        )
                                                      },
                                                      expression:
                                                        "manager.manager_prefix"
                                                    }
                                                  })
                                                ],
                                                1
                                              )
                                            ],
                                            1
                                          ),
                                          _vm._v(" "),
                                          _c(
                                            "v-col",
                                            { attrs: { cols: "3", md: "3" } },
                                            [
                                              _c(
                                                "v-col",
                                                {
                                                  staticClass: "pl-0 pt-0 pb-0",
                                                  attrs: { cols: "4", sm: "4" }
                                                },
                                                [
                                                  _c(
                                                    "label",
                                                    {
                                                      staticClass: "ft-normal"
                                                    },
                                                    [_vm._v("Name")]
                                                  )
                                                ]
                                              ),
                                              _vm._v(" "),
                                              _c(
                                                "v-col",
                                                {
                                                  staticClass: "p-0 ml-m4",
                                                  attrs: { cols: "8", sm: "8" }
                                                },
                                                [
                                                  _c("v-text-field", {
                                                    staticClass: "disabled-tag",
                                                    attrs: {
                                                      required: "",
                                                      rules: [
                                                        function(v) {
                                                          return (
                                                            !!v ||
                                                            "Manager name is required"
                                                          )
                                                        }
                                                      ],
                                                      disabled: _vm.formDisable.includes(
                                                        parentIndex
                                                      )
                                                        ? true
                                                        : false
                                                    },
                                                    model: {
                                                      value:
                                                        manager.manager_name,
                                                      callback: function($$v) {
                                                        _vm.$set(
                                                          manager,
                                                          "manager_name",
                                                          $$v
                                                        )
                                                      },
                                                      expression:
                                                        "manager.manager_name"
                                                    }
                                                  })
                                                ],
                                                1
                                              )
                                            ],
                                            1
                                          ),
                                          _vm._v(" "),
                                          _c(
                                            "v-col",
                                            { attrs: { cols: "3", md: "3" } },
                                            [
                                              _c(
                                                "v-col",
                                                {
                                                  staticClass: "pl-0 pt-0 pb-0",
                                                  attrs: { cols: "4", sm: "4" }
                                                },
                                                [
                                                  _c(
                                                    "label",
                                                    {
                                                      staticClass: "ft-normal"
                                                    },
                                                    [_vm._v("E-mail")]
                                                  )
                                                ]
                                              ),
                                              _vm._v(" "),
                                              _c(
                                                "v-col",
                                                {
                                                  staticClass: "p-0 ml-m4",
                                                  attrs: { cols: "8", sm: "8" }
                                                },
                                                [
                                                  _c("v-text-field", {
                                                    staticClass: "disabled-tag",
                                                    attrs: {
                                                      rules: _vm.emailRules,
                                                      disabled: _vm.formDisable.includes(
                                                        parentIndex
                                                      )
                                                        ? true
                                                        : false,
                                                      name: "email",
                                                      required: ""
                                                    },
                                                    model: {
                                                      value:
                                                        manager.manager_email,
                                                      callback: function($$v) {
                                                        _vm.$set(
                                                          manager,
                                                          "manager_email",
                                                          $$v
                                                        )
                                                      },
                                                      expression:
                                                        "manager.manager_email"
                                                    }
                                                  })
                                                ],
                                                1
                                              )
                                            ],
                                            1
                                          ),
                                          _vm._v(" "),
                                          _c(
                                            "v-col",
                                            { attrs: { cols: "3", md: "3" } },
                                            [
                                              _c(
                                                "v-col",
                                                {
                                                  staticClass: "pl-0 pt-0 pb-0",
                                                  attrs: { cols: "4", sm: "4" }
                                                },
                                                [
                                                  _c(
                                                    "label",
                                                    {
                                                      staticClass: "ft-normal"
                                                    },
                                                    [_vm._v("Phone")]
                                                  )
                                                ]
                                              ),
                                              _vm._v(" "),
                                              _c(
                                                "v-col",
                                                {
                                                  staticClass: "p-0 ml-m4",
                                                  attrs: { cols: "8", sm: "8" }
                                                },
                                                [
                                                  _c("v-text-field", {
                                                    staticClass: "disabled-tag",
                                                    attrs: {
                                                      rules: _vm.phoneRules,
                                                      disabled: _vm.formDisable.includes(
                                                        parentIndex
                                                      )
                                                        ? true
                                                        : false,
                                                      required: "",
                                                      maxlength: "10"
                                                    },
                                                    model: {
                                                      value:
                                                        manager.manager_phone,
                                                      callback: function($$v) {
                                                        _vm.$set(
                                                          manager,
                                                          "manager_phone",
                                                          $$v
                                                        )
                                                      },
                                                      expression:
                                                        "manager.manager_phone"
                                                    }
                                                  })
                                                ],
                                                1
                                              )
                                            ],
                                            1
                                          ),
                                          _vm._v(" "),
                                          _c(
                                            "v-col",
                                            { attrs: { cols: "3", md: "3" } },
                                            [
                                              _c(
                                                "v-col",
                                                {
                                                  staticClass: "pl-0 pt-0 pb-0",
                                                  attrs: { cols: "4", sm: "4" }
                                                },
                                                [
                                                  _c(
                                                    "label",
                                                    {
                                                      staticClass: "ft-normal"
                                                    },
                                                    [_vm._v("Address")]
                                                  )
                                                ]
                                              ),
                                              _vm._v(" "),
                                              _c(
                                                "v-col",
                                                {
                                                  staticClass: "p-0 ml-m4",
                                                  attrs: { cols: "8", sm: "8" }
                                                },
                                                [
                                                  _c("v-text-field", {
                                                    staticClass: "disabled-tag",
                                                    attrs: {
                                                      required: "",
                                                      rules: [
                                                        function(v) {
                                                          return (
                                                            !!v ||
                                                            "address is required"
                                                          )
                                                        }
                                                      ],
                                                      disabled: _vm.formDisable.includes(
                                                        parentIndex
                                                      )
                                                        ? true
                                                        : false
                                                    },
                                                    model: {
                                                      value:
                                                        manager.manager_address,
                                                      callback: function($$v) {
                                                        _vm.$set(
                                                          manager,
                                                          "manager_address",
                                                          $$v
                                                        )
                                                      },
                                                      expression:
                                                        "manager.manager_address"
                                                    }
                                                  })
                                                ],
                                                1
                                              )
                                            ],
                                            1
                                          ),
                                          _vm._v(" "),
                                          _c(
                                            "v-col",
                                            { attrs: { cols: "3", md: "3" } },
                                            [
                                              _c(
                                                "v-col",
                                                {
                                                  staticClass: "pl-0 pt-0 pb-0",
                                                  attrs: { cols: "4", sm: "4" }
                                                },
                                                [
                                                  _c(
                                                    "label",
                                                    {
                                                      staticClass: "ft-normal"
                                                    },
                                                    [_vm._v("City")]
                                                  )
                                                ]
                                              ),
                                              _vm._v(" "),
                                              _c(
                                                "v-col",
                                                {
                                                  staticClass: "p-0 ml-m4",
                                                  attrs: { cols: "8", sm: "8" }
                                                },
                                                [
                                                  _c("v-text-field", {
                                                    staticClass: "disabled-tag",
                                                    attrs: {
                                                      required: "",
                                                      rules: [
                                                        function(v) {
                                                          return (
                                                            !!v ||
                                                            "City is required"
                                                          )
                                                        }
                                                      ],
                                                      disabled: _vm.formDisable.includes(
                                                        parentIndex
                                                      )
                                                        ? true
                                                        : false
                                                    },
                                                    model: {
                                                      value:
                                                        manager.manager_city,
                                                      callback: function($$v) {
                                                        _vm.$set(
                                                          manager,
                                                          "manager_city",
                                                          $$v
                                                        )
                                                      },
                                                      expression:
                                                        "manager.manager_city"
                                                    }
                                                  })
                                                ],
                                                1
                                              )
                                            ],
                                            1
                                          ),
                                          _vm._v(" "),
                                          _c(
                                            "v-col",
                                            { attrs: { cols: "3", md: "3" } },
                                            [
                                              _c(
                                                "v-col",
                                                {
                                                  staticClass: "pl-0 pt-0 pb-0",
                                                  attrs: { cols: "4", sm: "4" }
                                                },
                                                [
                                                  _c(
                                                    "label",
                                                    {
                                                      staticClass: "ft-normal"
                                                    },
                                                    [_vm._v("State")]
                                                  )
                                                ]
                                              ),
                                              _vm._v(" "),
                                              _c(
                                                "v-col",
                                                {
                                                  staticClass: "p-0 ml-m4",
                                                  attrs: { cols: "8", sm: "8" }
                                                },
                                                [
                                                  _c("v-text-field", {
                                                    staticClass: "disabled-tag",
                                                    attrs: {
                                                      required: "",
                                                      rules: [
                                                        function(v) {
                                                          return (
                                                            !!v ||
                                                            "Province is required"
                                                          )
                                                        }
                                                      ],
                                                      disabled: _vm.formDisable.includes(
                                                        parentIndex
                                                      )
                                                        ? true
                                                        : false
                                                    },
                                                    model: {
                                                      value:
                                                        manager.manager_province,
                                                      callback: function($$v) {
                                                        _vm.$set(
                                                          manager,
                                                          "manager_province",
                                                          $$v
                                                        )
                                                      },
                                                      expression:
                                                        "manager.manager_province"
                                                    }
                                                  })
                                                ],
                                                1
                                              )
                                            ],
                                            1
                                          ),
                                          _vm._v(" "),
                                          _c(
                                            "v-col",
                                            { attrs: { cols: "3", md: "3" } },
                                            [
                                              _c(
                                                "v-col",
                                                {
                                                  staticClass: "pl-0 pt-0 pb-0",
                                                  attrs: { cols: "4", sm: "4" }
                                                },
                                                [
                                                  _c(
                                                    "label",
                                                    {
                                                      staticClass: "ft-normal"
                                                    },
                                                    [_vm._v("zipcode")]
                                                  )
                                                ]
                                              ),
                                              _vm._v(" "),
                                              _c(
                                                "v-col",
                                                {
                                                  staticClass: "p-0 ml-m4",
                                                  attrs: { cols: "8", sm: "8" }
                                                },
                                                [
                                                  _c("v-text-field", {
                                                    staticClass: "disabled-tag",
                                                    attrs: {
                                                      rules: [
                                                        function(v) {
                                                          return (
                                                            !!v ||
                                                            "Zip code is required"
                                                          )
                                                        }
                                                      ],
                                                      disabled: _vm.formDisable.includes(
                                                        parentIndex
                                                      )
                                                        ? true
                                                        : false,
                                                      required: ""
                                                    },
                                                    model: {
                                                      value:
                                                        manager.manager_zipcode,
                                                      callback: function($$v) {
                                                        _vm.$set(
                                                          manager,
                                                          "manager_zipcode",
                                                          $$v
                                                        )
                                                      },
                                                      expression:
                                                        "manager.manager_zipcode"
                                                    }
                                                  })
                                                ],
                                                1
                                              )
                                            ],
                                            1
                                          ),
                                          _vm._v(" "),
                                          _c(
                                            "v-col",
                                            { attrs: { cols: "3", md: "3" } },
                                            [
                                              _c(
                                                "v-col",
                                                {
                                                  staticClass: "pl-0 pt-0 pb-0",
                                                  attrs: { cols: "4", sm: "4" }
                                                },
                                                [
                                                  _c(
                                                    "label",
                                                    {
                                                      staticClass: "ft-normal"
                                                    },
                                                    [_vm._v("Id CardNo")]
                                                  )
                                                ]
                                              ),
                                              _vm._v(" "),
                                              _c(
                                                "v-col",
                                                {
                                                  staticClass: "p-0 ml-m4",
                                                  attrs: { cols: "8", sm: "8" }
                                                },
                                                [
                                                  _c("v-text-field", {
                                                    staticClass: "disabled-tag",
                                                    attrs: {
                                                      rules: [
                                                        function(v) {
                                                          return (
                                                            !!v ||
                                                            "Card Id number is required"
                                                          )
                                                        }
                                                      ],
                                                      disabled: _vm.formDisable.includes(
                                                        parentIndex
                                                      )
                                                        ? true
                                                        : false,
                                                      required: ""
                                                    },
                                                    model: {
                                                      value:
                                                        manager.manager_id_card,
                                                      callback: function($$v) {
                                                        _vm.$set(
                                                          manager,
                                                          "manager_id_card",
                                                          $$v
                                                        )
                                                      },
                                                      expression:
                                                        "manager.manager_id_card"
                                                    }
                                                  })
                                                ],
                                                1
                                              )
                                            ],
                                            1
                                          ),
                                          _vm._v(" "),
                                          _c(
                                            "v-col",
                                            { attrs: { cols: "4", md: "4" } },
                                            [
                                              _c("file-pond", {
                                                ref: "pond",
                                                refInFor: true,
                                                attrs: {
                                                  name: "uploadImage",
                                                  "label-idle":
                                                    "Upload Id Card Image",
                                                  "allow-multiple": "false",
                                                  server: _vm.serverOptions,
                                                  files: _vm.myFiles,
                                                  "allow-file-type-validation":
                                                    "true",
                                                  "accepted-file-types":
                                                    "image/jpeg, image/png",
                                                  disabled: _vm.formDisable.includes(
                                                    parentIndex
                                                  )
                                                    ? true
                                                    : false
                                                },
                                                on: {
                                                  addfilestart: function(
                                                    $event
                                                  ) {
                                                    return _vm.setUploadIndex(
                                                      parentIndex,
                                                      childIndex
                                                    )
                                                  },
                                                  processfile:
                                                    _vm.handleProcessFile3,
                                                  processfilerevert: function(
                                                    $event
                                                  ) {
                                                    return _vm.handleRemoveFile3(
                                                      parentIndex,
                                                      childIndex
                                                    )
                                                  }
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
                                  )
                                ]
                              }),
                              _vm._v(" "),
                              _c(
                                "v-col",
                                { attrs: { cols: "2", md: "2" } },
                                [
                                  _c("v-switch", {
                                    staticClass: "mx-2",
                                    attrs: { label: "Edit" },
                                    on: {
                                      click: function($event) {
                                        return _vm.enableForm(parentIndex)
                                      }
                                    }
                                  })
                                ],
                                1
                              ),
                              _vm._v(" "),
                              _c(
                                "v-btn",
                                {
                                  staticClass: "mr-4 custom-save-btn ml-4",
                                  attrs: {
                                    loading:
                                      _vm.loading == parentIndex ? true : false,
                                    disabled:
                                      _vm.loading == parentIndex ? true : false,
                                    color: "success"
                                  },
                                  on: {
                                    click: function($event) {
                                      return _vm.update(parentIndex)
                                    }
                                  }
                                },
                                [_vm._v("Submit")]
                              )
                            ],
                            2
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
            }),
            0
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

/***/ "./resources/js/components/admin/customer/tab/farm.vue":
/*!*************************************************************!*\
  !*** ./resources/js/components/admin/customer/tab/farm.vue ***!
  \*************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _farm_vue_vue_type_template_id_cfef2c74___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./farm.vue?vue&type=template&id=cfef2c74& */ "./resources/js/components/admin/customer/tab/farm.vue?vue&type=template&id=cfef2c74&");
/* harmony import */ var _farm_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./farm.vue?vue&type=script&lang=js& */ "./resources/js/components/admin/customer/tab/farm.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _farm_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _farm_vue_vue_type_template_id_cfef2c74___WEBPACK_IMPORTED_MODULE_0__["render"],
  _farm_vue_vue_type_template_id_cfef2c74___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/admin/customer/tab/farm.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/admin/customer/tab/farm.vue?vue&type=script&lang=js&":
/*!**************************************************************************************!*\
  !*** ./resources/js/components/admin/customer/tab/farm.vue?vue&type=script&lang=js& ***!
  \**************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_farm_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/babel-loader/lib??ref--4-0!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./farm.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/admin/customer/tab/farm.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_farm_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/admin/customer/tab/farm.vue?vue&type=template&id=cfef2c74&":
/*!********************************************************************************************!*\
  !*** ./resources/js/components/admin/customer/tab/farm.vue?vue&type=template&id=cfef2c74& ***!
  \********************************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_farm_vue_vue_type_template_id_cfef2c74___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../../../node_modules/vue-loader/lib??vue-loader-options!./farm.vue?vue&type=template&id=cfef2c74& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/admin/customer/tab/farm.vue?vue&type=template&id=cfef2c74&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_farm_vue_vue_type_template_id_cfef2c74___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_farm_vue_vue_type_template_id_cfef2c74___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);