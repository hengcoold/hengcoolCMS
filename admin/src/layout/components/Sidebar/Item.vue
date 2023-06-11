<script>
export default {
  name: 'MenuItem',
  functional: true,
  props: {
    icon: {
      type: String,
      default: ''
    },
    title: {
      type: String,
      default: ''
    }
  },
  render(h, context) {
    const { icon, title } = context.props
    const vnodes = []

    if (icon) {
      vnodes.push(<i class={'e-icon ' + icon}></i>)
      // if (icon.includes('el-icon')) {
      //   vnodes.push(<i class={[icon, 'sub-el-icon']} />)
      // } else {
      //   vnodes.push(<svg-icon icon-class={icon}/>)
      // }
    }

    if (title) {
      vnodes.push(
        <span slot='title' class='title'>
          {title}
        </span>
      )
    }
    return vnodes
  }
}
</script>

<style lang="scss" scoped>
.sub-el-icon {
  color: currentColor;
  width: 1em;
  height: 1em;
}
.el-menu--collapse {
  .e-icon {
    position: absolute;
    left: 50%;
    top: 50%;
    transform: translate(-50%, -50%);
  }
}
.el-menu {
  .e-icon{
    width: 14px;
    text-align: center
  }
  .title {
    padding-left: 20px;
  }
}
</style>
