import Cookies from 'js-cookie'

const TokenKey = 'Admin-Token-Cms-Blog'
const TokenTypeKey = 'Admin-Token-Type'
const UserInfo = 'Admin-Token-Cms-Blog-User-Info'
const ModelId = 'Admin-Model-Id-Cms-Blog'
const ModelName = 'Admin-Model-Name-Cms-Blog'
const MainInfo = 'Admin-Main-info-Blog'
export function getToken() {
  return Cookies.get(TokenKey)
}

export function setToken(token) {
  return Cookies.set(TokenKey, token)
}

export function removeToken() {
  return Cookies.remove(TokenKey)
}
export function getTokenType() {
  return Cookies.get(TokenTypeKey)
}

export function setTokenType(tokenType) {
  return Cookies.set(TokenTypeKey, tokenType)
}

export function removeTokenType() {
  return Cookies.remove(TokenTypeKey)
}
export function getUserInfo() {
  return Cookies.get(UserInfo)
}

export function setUserInfo(user) {
  return Cookies.set(UserInfo, user)
}

export function removeUserInfo() {
  return Cookies.remove(UserInfo)
}
export function getModelId() {
  return Cookies.get(ModelId)
}

export function setModelId(modelId) {
  return Cookies.set(ModelId, modelId)
}

export function removeModelId() {
  return Cookies.remove(ModelId)
}



export function getModelName() {
  return Cookies.get(ModelName)
}

export function setModelName(modelName) {
  return Cookies.set(ModelName, modelName)
}

export function removeModelName() {
  return Cookies.remove(ModelName)
}


export function getMainInfo() {
  return Cookies.get(MainInfo)
}

export function setMainInfo(mainInfo) {
  return Cookies.set(MainInfo, mainInfo)
}

export function removeMainInfo() {
  return Cookies.remove(MainInfo)
}
