/**
 * As we are using hash based navigation, hack fix
 * to highlight the current selected menu
 */
 function menuFix (slug) {
  const menuRoot = document.querySelector(`#toplevel_page_${slug}`)
  const currentUrl = window.location.href
  const currentPath = currentUrl.substr(currentUrl.indexOf('admin.php'))

  menuRoot.addEventListener('click', function (e) {
    const target = e.target
    this.querySelectorAll('li').forEach(
      function (node) {
        if (node !== e.target.parentElement) {
          node.classList.remove('current')
        } else {
          target.parentElement.classList.add('current')
        }
      }
    )
  })

  menuRoot.querySelector(`.wp-submenu a[href="${currentPath}"`).parentElement.classList.add('current')
}

export default menuFix
