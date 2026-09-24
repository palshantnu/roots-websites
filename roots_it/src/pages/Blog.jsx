import { useMemo, useState } from 'react';
import SEO from '../components/common/SEO';
import PageHero from '../components/templates/PageHero';
import Reveal from '../components/common/Reveal';
import BlogCard from '../components/cards/BlogCard';
import CTASection from '../components/sections/CTASection';
import { blogs, blogCategories } from '../data/blogs';

export default function Blog() {
  const [category, setCategory] = useState('All');

  const visible = useMemo(() => {
    const list =
      category === 'All' ? blogs : blogs.filter((b) => b.category === category);
    return [...list].sort((a, b) => new Date(b.date) - new Date(a.date));
  }, [category]);

  return (
    <>
      <SEO
        title="Blog"
        description="Practical articles on web and software development, mobile apps, SEO and digital marketing from the Roots Technology team."
      />

      <PageHero
        eyebrow="Blog"
        title="Notes from the team"
        subtitle="Practical, opinionated writing on building and growing digital products. No fluff, no listicles."
        trail={[{ label: 'Blog' }]}
      />

      <section className="section">
        <div className="container">
          <div className="filter-bar" role="tablist" aria-label="Filter posts by category">
            {blogCategories.map((cat) => (
              <button
                key={cat}
                role="tab"
                aria-selected={category === cat}
                className={`filter-chip ${category === cat ? 'is-active' : ''}`}
                onClick={() => setCategory(cat)}
              >
                {cat}
              </button>
            ))}
          </div>

          <div className="grid grid-3">
            {visible.map((post, i) => (
              <Reveal key={post.id} delay={(i % 3) * 0.06}>
                <BlogCard post={post} />
              </Reveal>
            ))}
          </div>

          {visible.length === 0 && (
            <p className="lead mx-auto text-center">No articles in this category yet.</p>
          )}
        </div>
      </section>

      <CTASection
        title="Prefer to talk it through?"
        text="If an article raised a question about your own project, we’re happy to get on a call."
        primary={{ label: 'Talk to an Expert', to: '/contact' }}
        secondary={{ label: 'View Services', to: '/services' }}
      />
    </>
  );
}
