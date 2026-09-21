export const categories = ['Fiction', 'Poetry', 'Academic', 'History', 'Religion', 'Motivational', 'Children', 'Drama', 'Research', 'Autobiography', 'Novel', 'Stories', 'Travelogue', 'Short Stories']

const covers = [
  ['The Art of Becoming', 'N. K. Sharma', 'Fiction', 499, 699, '#d6a36a'],
  ['A Sky Full of Stories', 'Meera Kapoor', 'Stories', 399, 599, '#75978a'],
  ['Letters to My Younger Self', 'Arjun Rai', 'Autobiography', 349, 499, '#c87b6a'],
  ['Nanka Pind', 'Satwinder Singh', 'History', 399, 499, '#9a6d55'],
  ['Samma Sati', 'Dr. Kamlesh Mani Chaudhary', 'Motivational', 399, 499, '#698b81'],
  ['Life in Twilight', 'Debjani Ghosh', 'Poetry', 190, 299, '#b8955b'],
  ['The Corporate Hanuman', 'Dr. Trilok Sharma', 'Religion', 499, 899, '#5c7186'],
  ['Pratidhwani Chhayasutram', 'Dr. Mayukh Mukherjee', 'Other Books', 549, 749, '#bb765a'],
  ['How To Play Cricket For Juniors', 'Aayavanth Mohanty', 'Children', 299, 399, '#779a7d'],
  ['Mastering Economics', 'Ritu Malhotra', 'Academic', 699, 899, '#7b7193'],
  ['Dil Ki Atut Yatra', 'Kavita Singh', 'Poetry', 299, 399, '#c08769'],
  ['Rising Above', 'Ashvita Garg', 'Motivational', 449, 599, '#60858c'],
]

export const books = covers.map(([title, author, category, price, originalPrice, color], index) => ({
  id: `book-${index + 1}`, slug: title.toLowerCase().replaceAll(' ', '-'), title, author, category, price, originalPrice, color,
  coverImage: `https://images.unsplash.com/photo-${['1544947950-fa07a98d237f','1512820790803-83ca734da794','1495446815901-a7297e633e8d','1521587760476-6c12a4b040da','1543002588-bfa74002ed7e','1544716278-ca5e3f4abd8c'][index % 6]}?auto=format&fit=crop&w=700&q=85`,
  isbn: `978-81-RTS-${String(1000 + index)}`, pages: 180 + index * 24, language: index % 3 === 0 ? 'Hindi' : 'English', format: 'Paperback', rating: 4 + (index % 2) * .5, reviewCount: 12 + index * 7, featured: index < 6, bestseller: index % 3 === 0, newRelease: index > 7,
  description: `A carefully crafted ${category.toLowerCase()} title about memory, possibility and the small choices that shape a life. Prepared with editorial care by RTS Publication.`
}))

export const authors = [
  ['Raaj Heeraman', 'Author / Poet', 'Poetry and Literature'], ['Dr. Achla Nagar', 'Author', 'Fiction'], ['Ajay Agrawal', 'Author', 'Academic'], ['Dr. Sanjeev Kumar Chaudhary', 'Professor of Surgery', 'Research'], ['Varsha Chopade', 'Author, Social Activist', 'Autobiography'], ['Dr. Jhorna Sharma', 'Assistant Professor', 'Academic'], ['Ashvita Garg', 'Motivational Speaker', 'Motivational'], ['Satish Vimal', 'Poet', 'Poetry'],
].map(([name, role, genre], index) => ({ id: `author-${index + 1}`, slug: name.toLowerCase().replaceAll(' ', '-'), name, role, genre, books: 2 + index, bio: 'A thoughtful voice with a body of work that invites readers to pause, question and see the familiar differently.', image: ['1500648767791-00dcc994a43e','1551836022-d5d88e9218df','1560250097-0b93528c311a','1535713875002-d1d0cf377fde','1580489944761-15a19d654956'][index % 5] }))

export const services = [
  ['Book Publishing', 'From manuscript to a finished book with a clear, supportive process.'], ['Cover Design', 'A considered cover that catches attention and carries your story.'], ['Editing & Proofreading', 'Editorial guidance that makes your words precise and confident.'], ['Interior Design', 'Readable, beautiful page design across print and digital formats.'], ['ISBN Assistance', 'Practical support through publishing identifiers and metadata.'], ['Print & Distribution', 'Reach readers in India and across the world.'], ['E-book Publishing', 'Store-ready digital editions for modern readers.'], ['Marketing & Publicity', 'Build a discoverable author presence with a tailored plan.'], ['Book Launch Support', 'A thoughtful launch that gives your work a strong beginning.'],
].map(([title, description], index) => ({ id: `service-${index + 1}`, slug: title.toLowerCase().replaceAll(' ', '-').replaceAll('&-', ''), title, description }))

export const packages = [
  { name: 'Basic', price: '₹19,999', tone: 'sage', features: ['ISBN assistance', 'Basic cover design', 'Paperback setup', 'Author copies', 'Online distribution'] },
  { name: 'Professional', price: '₹39,999', tone: 'coral', popular: true, features: ['Everything in Basic', 'Editorial proofreading', 'Premium cover design', 'E-book conversion', 'Marketing starter kit', 'Global distribution'] },
  { name: 'Premium', price: '₹69,999', tone: 'ink', features: ['Everything in Professional', 'Interior design', 'Launch support', 'Author publicity', 'Audiobook consultation', 'Priority account support'] },
]

export const posts = [
  { title: 'How to prepare your manuscript for publication', category: 'Writing', date: '12 Aug 2024', time: '6 min read', color: '#d6a36a' },
  { title: 'Finding the right cover for your story', category: 'Design', date: '03 Aug 2024', time: '4 min read', color: '#75978a' },
  { title: 'Why every first-time author needs an editor', category: 'Publishing', date: '28 Jul 2024', time: '5 min read', color: '#c87b6a' },
]
