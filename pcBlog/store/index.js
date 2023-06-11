export const state = ()=>({
    typeList : []
})
export const mutations = {
    SET_TYPE_LIST(state,typeList){
        state.typeList = typeList
    }
 }
export const actions = {
    // nuxtServerInit(store,context){
    //     console.log('nuxtServerInit')
    // }
     UPDATE_TYPE_LIST({commit,state},typeList){
            commit('SET_TYPE_LIST',typeList)        
    }
}
export const getters = {
    getTypeList(state){
        return state.typeList
    }
}