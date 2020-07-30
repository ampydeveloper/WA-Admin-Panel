(window["webpackJsonp"] = window["webpackJsonp"] || []).push([[15],{

/***/ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/layout/AppBar.vue?vue&type=script&lang=js&":
/*!************************************************************************************************************************************************************************!*\
  !*** ./node_modules/babel-loader/lib??ref--4-0!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/layout/AppBar.vue?vue&type=script&lang=js& ***!
  \************************************************************************************************************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _services_authentication_service__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../../_services/authentication.service */ "./resources/js/_services/authentication.service.js");
/* harmony import */ var _helpers_router__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ../../_helpers/router */ "./resources/js/_helpers/router.js");
/* harmony import */ var _config_test_env__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../config/test.env */ "./resources/js/config/test.env.js");
/* harmony import */ var vuetify_lib__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! vuetify/lib */ "./node_modules/vuetify/lib/index.js");
/* harmony import */ var vuex__WEBPACK_IMPORTED_MODULE_4__ = __webpack_require__(/*! vuex */ "./node_modules/vuex/dist/vuex.esm.js");
/* harmony import */ var vue_feather_icons__WEBPACK_IMPORTED_MODULE_5__ = __webpack_require__(/*! vue-feather-icons */ "./node_modules/vue-feather-icons/dist/vue-feather-icons.es.js");
function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//
//


 // Components

 // Utilities



/* harmony default export */ __webpack_exports__["default"] = ({
  name: "DashboardCoreAppBar",
  components: {
    UserIcon: vue_feather_icons__WEBPACK_IMPORTED_MODULE_5__["UserIcon"],
    LogOutIcon: vue_feather_icons__WEBPACK_IMPORTED_MODULE_5__["LogOutIcon"],
    UserPlusIcon: vue_feather_icons__WEBPACK_IMPORTED_MODULE_5__["UserPlusIcon"],
    ListIcon: vue_feather_icons__WEBPACK_IMPORTED_MODULE_5__["ListIcon"],
    Edit3Icon: vue_feather_icons__WEBPACK_IMPORTED_MODULE_5__["Edit3Icon"],
    BellIcon: vue_feather_icons__WEBPACK_IMPORTED_MODULE_5__["BellIcon"],
    AppBarItem: {
      render: function render(h) {
        var _this = this;

        return h(vuetify_lib__WEBPACK_IMPORTED_MODULE_3__["VHover"], {
          scopedSlots: {
            "default": function _default(_ref) {
              var hover = _ref.hover;
              return h(vuetify_lib__WEBPACK_IMPORTED_MODULE_3__["VListItem"], {
                attrs: _this.$attrs,
                "class": {
                  "black--text": !hover,
                  "white--text secondary elevation-12": hover
                },
                props: _objectSpread({
                  activeClass: "",
                  dark: hover,
                  link: true
                }, _this.$attrs)
              }, _this.$slots["default"]);
            }
          }
        });
      }
    }
  },
  props: {
    value: {
      type: Boolean,
      "default": false
    }
  },
  data: function data() {
    return {
      profileImage: "",
      isManager: false,
      isDriver: false,
      isAdmin: false,
      userdata: ''
    };
  },
  created: function created() {
    var currentUser = JSON.parse(localStorage.getItem("currentUser"));
    this.userdata = currentUser.data.user;

    if (currentUser.data.user.role_id == 1) {
      this.isAdmin = true;
    } else if (currentUser.data.user.role_id == 2) {
      this.isManager = true;
    } else if (currentUser.data.user.role_id == 3) {
      this.isDriver = true;
    } else {
      this.isAdmin = true;
    }

    this.loadProfileImage();
  },
  computed: _objectSpread({}, Object(vuex__WEBPACK_IMPORTED_MODULE_4__["mapState"])(["drawer"])),
  watch: {
    'drawer': function drawer(newVal) {
      if (this.drawer) {
        document.getElementById("app-bar").style.width = "calc(100% - 260px)";
      } else {
        document.getElementById("app-bar").style.width = "unset";
      }
    }
  },
  methods: _objectSpread(_objectSpread({}, Object(vuex__WEBPACK_IMPORTED_MODULE_4__["mapMutations"])({
    setDrawer: "SET_DRAWER"
  })), {}, {
    loadProfileImage: function loadProfileImage() {
      var currentUser = JSON.parse(localStorage.getItem("currentUser"));

      if (currentUser.data.user.user_image) {
        this.profileImage = "../../" + currentUser.data.user.user_image;
      } else {
        this.profileImage = "/images/avatar.png";
      }
    },
    logout: function logout() {
      _services_authentication_service__WEBPACK_IMPORTED_MODULE_0__["authenticationService"].logout();
      _helpers_router__WEBPACK_IMPORTED_MODULE_1__["router"].push("/login");
    }
  })
});

/***/ }),

/***/ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/layout/AppBar.vue?vue&type=template&id=687bb11c&":
/*!****************************************************************************************************************************************************************************************************************!*\
  !*** ./node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!./node_modules/vue-loader/lib??vue-loader-options!./resources/js/components/layout/AppBar.vue?vue&type=template&id=687bb11c& ***!
  \****************************************************************************************************************************************************************************************************************/
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
                      },
                      scopedSlots: _vm._u([
                        {
                          key: "activator",
                          fn: function(ref) {
                            var on = ref.on
                            return [
                              _c("span", { staticClass: "logged-name" }, [
                                _c("span", { staticClass: "log-name" }, [
                                  _vm._v(
                                    "\n\t\t" +
                                      _vm._s(_vm.userdata.first_name) +
                                      " " +
                                      _vm._s(_vm.userdata.last_name) +
                                      "\n                "
                                  )
                                ]),
                                _vm._v(" "),
                                _vm.isAdmin
                                  ? _c("span", [_vm._v("Admin")])
                                  : _vm._e(),
                                _vm._v(" "),
                                _vm.isManager
                                  ? _c("span", [_vm._v("Manager")])
                                  : _vm._e(),
                                _vm._v(" "),
                                _vm.isDriver
                                  ? _c("span", [_vm._v("Driver")])
                                  : _vm._e()
                              ]),
                              _vm._v(" "),
                              _c("v-list-item-avatar", _vm._g({}, on), [
                                _c("img", {
                                  attrs: {
                                    src: _vm.profileImage,
                                    id: "userImage"
                                  }
                                })
                              ])
                            ]
                          }
                        }
                      ])
                    },
                    [
                      _vm._v(" "),
                      _c(
                        "v-list",
                        { staticClass: "header-right-menu" },
                        [
                          _c(
                            "v-list-item",
                            [
                              _vm.isAdmin
                                ? _c(
                                    "v-list-item-title",
                                    [
                                      _c(
                                        "router-link",
                                        {
                                          staticClass: "nav-item nav-link",
                                          attrs: { to: "/admin/profile" }
                                        },
                                        [
                                          _c("user-icon", {
                                            staticClass: "custom-class",
                                            attrs: { size: "1.5x" }
                                          }),
                                          _vm._v("\nProfile")
                                        ],
                                        1
                                      )
                                    ],
                                    1
                                  )
                                : _vm._e(),
                              _vm._v(" "),
                              _vm.isAdmin
                                ? _c(
                                    "v-list-item-title",
                                    [
                                      _c(
                                        "router-link",
                                        {
                                          staticClass: "nav-item nav-link",
                                          attrs: { to: "/admin/changepassword" }
                                        },
                                        [
                                          _c("edit-3-icon", {
                                            staticClass: "custom-class",
                                            attrs: { size: "1.5x" }
                                          }),
                                          _vm._v("\nChange Password")
                                        ],
                                        1
                                      )
                                    ],
                                    1
                                  )
                                : _vm._e(),
                              _vm._v(" "),
                              _vm.isManager
                                ? _c(
                                    "v-list-item-title",
                                    [
                                      _c(
                                        "router-link",
                                        {
                                          staticClass: "nav-item nav-link",
                                          attrs: { to: "/manager/profile" }
                                        },
                                        [
                                          _c("user-icon", {
                                            staticClass: "custom-class",
                                            attrs: { size: "1.5x" }
                                          }),
                                          _vm._v("\nProfile")
                                        ],
                                        1
                                      )
                                    ],
                                    1
                                  )
                                : _vm._e(),
                              _vm._v(" "),
                              _vm.isManager
                                ? _c(
                                    "v-list-item-title",
                                    [
                                      _c(
                                        "router-link",
                                        {
                                          staticClass: "nav-item nav-link",
                                          attrs: {
                                            to: "/manager/changepassword"
                                          }
                                        },
                                        [_vm._v("Change Password")]
                                      )
                                    ],
                                    1
                                  )
                                : _vm._e(),
                              _vm._v(" "),
                              _vm.isDriver
                                ? _c(
                                    "v-list-item-title",
                                    [
                                      _c(
                                        "router-link",
                                        {
                                          staticClass: "nav-item nav-link",
                                          attrs: { to: "/driver/profile" }
                                        },
                                        [
                                          _c("user-icon", {
                                            staticClass: "custom-class",
                                            attrs: { size: "1.5x" }
                                          }),
                                          _vm._v("Profile")
                                        ],
                                        1
                                      )
                                    ],
                                    1
                                  )
                                : _vm._e(),
                              _vm._v(" "),
                              _vm.isDriver
                                ? _c(
                                    "v-list-item-title",
                                    [
                                      _c(
                                        "router-link",
                                        {
                                          staticClass: "nav-item nav-link",
                                          attrs: {
                                            to: "/driver/changepassword"
                                          }
                                        },
                                        [
                                          _c("edit-3-icon", {
                                            staticClass: "custom-class",
                                            attrs: { size: "1.5x" }
                                          }),
                                          _vm._v("\nChange Password")
                                        ],
                                        1
                                      )
                                    ],
                                    1
                                  )
                                : _vm._e(),
                              _vm._v(" "),
                              _vm.isAdmin
                                ? _c(
                                    "v-list-item-title",
                                    [
                                      _c(
                                        "router-link",
                                        {
                                          staticClass: "nav-item nav-link",
                                          attrs: { to: "/admin/admin/add" }
                                        },
                                        [
                                          _c("user-plus-icon", {
                                            staticClass: "custom-class",
                                            attrs: { size: "1.5x" }
                                          }),
                                          _vm._v("\nAdd Admin")
                                        ],
                                        1
                                      )
                                    ],
                                    1
                                  )
                                : _vm._e(),
                              _vm._v(" "),
                              _vm.isAdmin
                                ? _c(
                                    "v-list-item-title",
                                    [
                                      _c(
                                        "router-link",
                                        {
                                          staticClass: "nav-item nav-link",
                                          attrs: { to: "/admin/admin" }
                                        },
                                        [
                                          _c("list-icon", {
                                            staticClass: "custom-class",
                                            attrs: { size: "1.5x" }
                                          }),
                                          _vm._v("\nList Admin")
                                        ],
                                        1
                                      )
                                    ],
                                    1
                                  )
                                : _vm._e(),
                              _vm._v(" "),
                              _c("v-list-item-title", [
                                _c(
                                  "button",
                                  {
                                    staticClass: "nav-item nav-link",
                                    attrs: { type: "button" },
                                    on: { click: _vm.logout }
                                  },
                                  [
                                    _c("log-out-icon", {
                                      staticClass: "custom-class",
                                      attrs: { size: "1.5x" }
                                    }),
                                    _vm._v("\n Logout")
                                  ],
                                  1
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
              ),
              _vm._v(" "),
              _c(
                "span",
                { staticClass: "notification" },
                [
                  _c("bell-icon", {
                    staticClass: "custom-class",
                    attrs: { size: "1.5x" }
                  }),
                  _c("span", { staticClass: "count" }, [_vm._v("5")])
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
        "div",
        { staticClass: "header-right" },
        [
          !_vm.drawer
            ? _c(
                "v-icon",
                {
                  on: {
                    click: function($event) {
                      return _vm.setDrawer(!_vm.drawer)
                    }
                  }
                },
                [_vm._v("mdi-format-indent-increase")]
              )
            : _vm._e(),
          _vm._v(" "),
          _vm.drawer
            ? _c(
                "v-icon",
                {
                  on: {
                    click: function($event) {
                      return _vm.setDrawer(!_vm.drawer)
                    }
                  }
                },
                [_vm._v("mdi-format-indent-decrease")]
              )
            : _vm._e(),
          _vm._v(" "),
          _c("span", { staticClass: "page-title" }, [_vm._v("Overview")])
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

/***/ "./resources/js/components/layout/AppBar.vue":
/*!***************************************************!*\
  !*** ./resources/js/components/layout/AppBar.vue ***!
  \***************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _AppBar_vue_vue_type_template_id_687bb11c___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./AppBar.vue?vue&type=template&id=687bb11c& */ "./resources/js/components/layout/AppBar.vue?vue&type=template&id=687bb11c&");
/* harmony import */ var _AppBar_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./AppBar.vue?vue&type=script&lang=js& */ "./resources/js/components/layout/AppBar.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport *//* harmony import */ var _node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ../../../../node_modules/vue-loader/lib/runtime/componentNormalizer.js */ "./node_modules/vue-loader/lib/runtime/componentNormalizer.js");





/* normalize component */

var component = Object(_node_modules_vue_loader_lib_runtime_componentNormalizer_js__WEBPACK_IMPORTED_MODULE_2__["default"])(
  _AppBar_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_1__["default"],
  _AppBar_vue_vue_type_template_id_687bb11c___WEBPACK_IMPORTED_MODULE_0__["render"],
  _AppBar_vue_vue_type_template_id_687bb11c___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"],
  false,
  null,
  null,
  null
  
)

/* hot reload */
if (false) { var api; }
component.options.__file = "resources/js/components/layout/AppBar.vue"
/* harmony default export */ __webpack_exports__["default"] = (component.exports);

/***/ }),

/***/ "./resources/js/components/layout/AppBar.vue?vue&type=script&lang=js&":
/*!****************************************************************************!*\
  !*** ./resources/js/components/layout/AppBar.vue?vue&type=script&lang=js& ***!
  \****************************************************************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AppBar_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/babel-loader/lib??ref--4-0!../../../../node_modules/vue-loader/lib??vue-loader-options!./AppBar.vue?vue&type=script&lang=js& */ "./node_modules/babel-loader/lib/index.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/layout/AppBar.vue?vue&type=script&lang=js&");
/* empty/unused harmony star reexport */ /* harmony default export */ __webpack_exports__["default"] = (_node_modules_babel_loader_lib_index_js_ref_4_0_node_modules_vue_loader_lib_index_js_vue_loader_options_AppBar_vue_vue_type_script_lang_js___WEBPACK_IMPORTED_MODULE_0__["default"]); 

/***/ }),

/***/ "./resources/js/components/layout/AppBar.vue?vue&type=template&id=687bb11c&":
/*!**********************************************************************************!*\
  !*** ./resources/js/components/layout/AppBar.vue?vue&type=template&id=687bb11c& ***!
  \**********************************************************************************/
/*! exports provided: render, staticRenderFns */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AppBar_vue_vue_type_template_id_687bb11c___WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! -!../../../../node_modules/vue-loader/lib/loaders/templateLoader.js??vue-loader-options!../../../../node_modules/vue-loader/lib??vue-loader-options!./AppBar.vue?vue&type=template&id=687bb11c& */ "./node_modules/vue-loader/lib/loaders/templateLoader.js?!./node_modules/vue-loader/lib/index.js?!./resources/js/components/layout/AppBar.vue?vue&type=template&id=687bb11c&");
/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "render", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AppBar_vue_vue_type_template_id_687bb11c___WEBPACK_IMPORTED_MODULE_0__["render"]; });

/* harmony reexport (safe) */ __webpack_require__.d(__webpack_exports__, "staticRenderFns", function() { return _node_modules_vue_loader_lib_loaders_templateLoader_js_vue_loader_options_node_modules_vue_loader_lib_index_js_vue_loader_options_AppBar_vue_vue_type_template_id_687bb11c___WEBPACK_IMPORTED_MODULE_0__["staticRenderFns"]; });



/***/ })

}]);