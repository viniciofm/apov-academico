const acl_laravel = JSON.parse(Laravel)

const Acl = {
    install(Vue, options) {
        Vue.directive('can', function (el, binding) {
            let result_acl = acl_laravel[binding.value]

            if (!_.isBoolean(result_acl) || !result_acl) {
                el.style.display = 'none';
            }
        })

        Vue.prototype.$can = function (permission) {
            let result_acl = acl_laravel[permission]

            if (_.isBoolean(result_acl)) {
                return result_acl;
            }

            return false;
        }
    }
};

export default Acl
