import { HTTP } from './http-common'

export function searchID () {
    let path = window.location.pathname;
    let arr = path.split('/');
    return arr[arr.length - 1];
}

export function toSeek (url) {
    return new Promise((resolve, reject) => {
        HTTP({
            method: 'GET',
            url: url
        }).then(
            response => resolve(response.data.data)
        ).catch(
            e => reject(e)
        )
    })
}

export function deleteData (url, id, _method) {
    Pace.restart()
    loading('loading')

    let data = new FormData()
    data.set('id', id)
    data.set('_method', _method)

    return new Promise((resolve, reject) => {
        Pace.track(() => {
            HTTP({
                method: 'POST',
                url: url,
                data: data,
            }).then(
                response => {
                    loading()
                    resolve(response.data.data)
                }
            ).catch(
                error => {
                    loading()
                    reject(error)
                }
            )
        })
    });
}

/**
 * Submit a partir de dados em formulário HTML
 *
 * @param url
 * @param idForm
 * @returns {Promise}
 */
export function submitForm (url, idForm) {
    Pace.restart()
    loading('loading')

    /* remove div errors caso exista */
    let div = document.getElementById('errors')
    if (null !== div) div.remove()

    let form = document.getElementById(idForm)
    let data = new FormData(form)

    return new Promise((resolve, reject) => {
        Pace.track(() => {
            HTTP({
                method: 'POST',
                url: url,
                data: data,
            }).then(
                response => {
                    loading()
                    resolve(response.data)
                }
            ).catch(
                error => {
                    loading()
                    let div = errorMessage(error)
                    form.insertBefore(div, form.firstChild)
                    reject(error)
                }
            )
        })
    })
}

/**
 * Submit a partir de dados em State Vue.js
 *
 * @param url
 * @param dataState
 * @param _method
 * @returns {Promise}
 */
export function submit (url, dataState, _method) {
    Pace.restart()
    loading('loading')

    /* remove div errors caso exista */
    let div = document.getElementById('errors')
    if (null !== div) div.remove()

    let data = new FormData()

    for (let key in dataState) {
        if (_.isObject(dataState[key])){
            data.set(key, JSON.stringify(dataState[key]))
        } else {
            data.set(key, dataState[key])
        }
    }

    data.set('_method', _method)

    return new Promise((resolve, reject) => {
        Pace.track(() => {
            HTTP({
                method: 'POST',
                url: url,
                data: data,
            }).then(
                response => {
                    resolve(response.data)
                    loading()
                }
            ).catch(
                error => {
                    loading()
                    reject(error)
                }
            )
        })
    })
}

/**
 * @param error
 * @returns {Element}
 */
function errorMessage (error) {
    let div = document.getElementById('errors')
    if (null !== div) div.remove()

    div = document.createElement('div')
    div.setAttribute('id', 'errors')
    div.classList.add('note', 'note-danger')

    let h4 = document.createElement('h4')
    let title = document.createTextNode('Encontramos alguns erros no preenchimento do formulário! Verifique os seguintes itens:')
    h4.classList.add('block')
    h4.appendChild(title)

    let errorBody

    if (undefined !== error.response && error.response.status === 422) {
        let response = error.response.data.errors
        let ul = document.createElement('ul')
        ul.classList.add('list-unstyled')

        Object.keys(response).map((objectKey, index) => {
            let li = document.createElement('li')
            let i = document.createElement('i')
            let text = document.createTextNode(response[objectKey].toString())

            i.classList.add('fa', 'fa-angle-double-right')
            li.appendChild(i)
            li.appendChild(text)
            ul.appendChild(li)
            errorBody = ul
        })
    } else {
        let p = document.createElement('p')
        let text = document.createTextNode(`Erro desconhecido`)

        if (undefined !== error.response) {
            text = document.createTextNode(`Erro ${error.response.status}, ${error.response.data.message}`)
        } else {
            console.dir(error)
        }

        p.appendChild(text)
        errorBody = p
    }

    div.appendChild(h4)
    div.appendChild(errorBody)

    return div
}


function loading(state = false) {
    let buttons = document.querySelectorAll('.btn-loading')

    if(state === 'loading') {
        for (let button of buttons) {
            button.classList.add('m-loader', 'm-loader--right', 'm-loader--light')
            button.setAttribute('aria-disabled', true)
            button.setAttribute('disabled', 'disabled')
        }
    } else {
        for (let button of buttons) {
            button.classList.remove('m-loader', 'm-loader--right', 'm-loader--light')
            button.removeAttribute('aria-disabled')
            button.removeAttribute('disabled')
        }
    }
}