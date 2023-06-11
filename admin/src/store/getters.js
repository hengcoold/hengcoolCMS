const getters = {
  sidebar: state => state.app.sidebar,
  language: state => state.app.language,
  size: state => state.app.size,
  device: state => state.app.device,
  visitedViews: state => state.tagsView.visitedViews,
  cachedViews: state => state.tagsView.cachedViews,
  token: state => state.user.token,
  tokenType: state => state.user.tokenType,
  userInfo: state => state.user.userInfo,
  roles: state => state.user.roles,
  permission_routes: state => state.permission.routes,
  addRoutes: state => state.permission.addRoutes,
  modelArr: state => state.permission.modelArr,
  mainInfo: state => state.permission.mainInfo,
  modelName: state => state.permission.modelName,
  errorLogs: state => state.errorLog.logs
}
export default getters
