/**
 * String -> icon component maps.
 *
 * Data files store icons as plain strings (e.g. icon: "code") so the content
 * stays 100% serializable and API/CMS-ready. Components render them through
 * <Icon name="code" /> and <TechIcon name="react" />.
 */
import {
  FiCode,
  FiMonitor,
  FiSmartphone,
  FiLayers,
  FiSettings,
  FiShoppingCart,
  FiTrendingUp,
  FiSearch,
  FiShare2,
  FiTarget,
  FiMail,
  FiEdit3,
  FiAward,
  FiUsers,
  FiClock,
  FiShield,
  FiHeadphones,
  FiZap,
  FiCloud,
  FiDatabase,
  FiGrid,
  FiGlobe,
  FiCpu,
  FiBarChart2,
  FiCheckCircle,
  FiRefreshCw,
  FiPenTool,
  FiMapPin,
  FiPhone,
  FiLayout,
  FiServer,
  FiActivity,
  FiThumbsUp,
  FiLock,
  FiPackage,
  FiCompass,
  FiClipboard,
  FiFeather,
  FiEye,
  FiHeart,
  FiCircle,
} from 'react-icons/fi';

import {
  SiReact,
  SiNextdotjs,
  SiJavascript,
  SiTypescript,
  SiHtml5,
  SiCss,
  SiNodedotjs,
  SiExpress,
  SiPhp,
  SiPython,
  SiMongodb,
  SiMysql,
  SiPostgresql,
  SiFirebase,
  SiVercel,
  SiFlutter,
  SiKotlin,
  SiSwift,
} from 'react-icons/si';
import { FaAws } from 'react-icons/fa';

const icons = {
  code: FiCode,
  monitor: FiMonitor,
  smartphone: FiSmartphone,
  layers: FiLayers,
  settings: FiSettings,
  cart: FiShoppingCart,
  trending: FiTrendingUp,
  search: FiSearch,
  share: FiShare2,
  target: FiTarget,
  mail: FiMail,
  edit: FiEdit3,
  award: FiAward,
  users: FiUsers,
  clock: FiClock,
  shield: FiShield,
  support: FiHeadphones,
  zap: FiZap,
  cloud: FiCloud,
  database: FiDatabase,
  grid: FiGrid,
  globe: FiGlobe,
  cpu: FiCpu,
  chart: FiBarChart2,
  check: FiCheckCircle,
  refresh: FiRefreshCw,
  pen: FiPenTool,
  pin: FiMapPin,
  phone: FiPhone,
  layout: FiLayout,
  server: FiServer,
  activity: FiActivity,
  thumbsUp: FiThumbsUp,
  lock: FiLock,
  package: FiPackage,
  compass: FiCompass,
  clipboard: FiClipboard,
  feather: FiFeather,
  eye: FiEye,
  heart: FiHeart,
};

const techIcons = {
  react: SiReact,
  nextjs: SiNextdotjs,
  javascript: SiJavascript,
  typescript: SiTypescript,
  html: SiHtml5,
  css: SiCss,
  node: SiNodedotjs,
  express: SiExpress,
  php: SiPhp,
  python: SiPython,
  mongodb: SiMongodb,
  mysql: SiMysql,
  postgresql: SiPostgresql,
  firebase: SiFirebase,
  aws: FaAws,
  vercel: SiVercel,
  'react-native': SiReact,
  flutter: SiFlutter,
  android: SiKotlin,
  ios: SiSwift,
};

export function Icon({ name, ...props }) {
  const Cmp = icons[name] || FiCircle;
  return <Cmp aria-hidden="true" {...props} />;
}

export function TechIcon({ name, ...props }) {
  const Cmp = techIcons[name] || FiPackage;
  return <Cmp aria-hidden="true" {...props} />;
}

export default Icon;
