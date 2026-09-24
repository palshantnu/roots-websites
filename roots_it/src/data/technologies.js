/**
 * Technology stack, grouped. `icon` keys resolve via <TechIcon /> in
 * src/utils/iconMap.jsx.
 */
export const technologyGroups = [
  {
    label: 'Frontend',
    items: [
      { name: 'React', icon: 'react' },
      { name: 'Next.js', icon: 'nextjs' },
      { name: 'JavaScript', icon: 'javascript' },
      { name: 'TypeScript', icon: 'typescript' },
      { name: 'HTML5', icon: 'html' },
      { name: 'CSS3', icon: 'css' },
    ],
  },
  {
    label: 'Backend',
    items: [
      { name: 'Node.js', icon: 'node' },
      { name: 'Express.js', icon: 'express' },
      { name: 'PHP', icon: 'php' },
      { name: 'Python', icon: 'python' },
    ],
  },
  {
    label: 'Mobile',
    items: [
      { name: 'React Native', icon: 'react-native' },
      { name: 'Android', icon: 'android' },
      { name: 'iOS', icon: 'ios' },
    ],
  },
  {
    label: 'Database',
    items: [
      { name: 'MongoDB', icon: 'mongodb' },
      { name: 'MySQL', icon: 'mysql' },
      { name: 'PostgreSQL', icon: 'postgresql' },
      { name: 'Firebase', icon: 'firebase' },
    ],
  },
  {
    label: 'Cloud & DevOps',
    items: [
      { name: 'AWS', icon: 'aws' },
      { name: 'Firebase', icon: 'firebase' },
      { name: 'Vercel', icon: 'vercel' },
    ],
  },
];

export default technologyGroups;
