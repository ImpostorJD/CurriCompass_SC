/**
 * This is used to create a template for navigation items
 */
export default interface NavigationItems {
  name: string;
  path: string;
  allowedRoles: Array<string>;
  icon_type: string;
  icon: string;
  hoverGroup: string;

}
