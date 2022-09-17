const Storage = window.localStorage

function localStorageExpires() {
    let toRemove = [],                      //Itens para serem removidos
        currentDate = new Date().getTime()  //Data atual em milissegundos

    for (let i = 0, j = Storage.length; i < j; i++) {
        let current = Storage.getItem(Storage.key(i))

        //Verifica se o formato do item para evitar conflitar com outras aplicações
        if (current && /^\{(.*?)\}$/.test(current)) {

            //Decodifica de volta para JSON
            current = JSON.parse(current)

            //Checa a chave expires do item especifico se for mais antigo que a data atual ele salva no array
            if (current.expires && current.expires <= currentDate) {
                toRemove.push(Storage.key(i))
            }
        }
    }

    // Remove itens que já passaram do tempo
    // Se remover no primeiro loop isto poderia afetar a ordem,
    // pois quando se remove um item geralmente o objeto ou array são reordenados
    for (let i = toRemove.length - 1; i >= 0; i--) {
        Storage.removeItem(toRemove[i])
    }
}

localStorageExpires()

class LocalStorage {
    constructor(namespace = '') {
        this.namespace = namespace
    }

    setItem (lastname = '', value = {}, mim = 240) {
        let expirarem = (new Date().getTime()) + (60000 * mim);

        Storage.setItem(`${this.namespace}${lastname}`, JSON.stringify({
            value: value,
            expires: expirarem
        }))
    }

    getItem (lastname) {
        localStorageExpires()  //Limpa itens

        let itemValue = Storage[`${this.namespace}${lastname}`]

        if (itemValue && /^\{(.*?)\}$/.test(itemValue)) {

            //Decodifica de volta para JSON
            let current = JSON.parse(itemValue)

            return current.value;
        }

        return false;
    }

    getAll () {
        let local = []

        for ( let i = 0, len = Storage.length; i < len; ++i ) {
            let itemKey = Storage.key(i)

            //Separando por namespace
            if (this.namespace === itemKey.substring(0, this.namespace.length)){
                let itemValue = Storage.getItem(itemKey)

                //Decodifica de volta para JSON
                let pushValue = JSON.parse(itemValue)

                //Salvando no array local
                local.push(pushValue.value)
            }
        }

        return local;
    }

    removeItem (lastname) {
        //Remove Item a partir da chave passada
        Storage.removeItem(`${this.namespace}${lastname}`)
    }

    clear () {
        //Remove tudo
        Storage.clear()
    }
}

export default LocalStorage