import { useMemo, useState } from 'react';
import SEO from '../components/common/SEO';
import PageHero from '../components/templates/PageHero';
import Reveal from '../components/common/Reveal';
import AsyncState from '../components/common/AsyncState';
import BlogCard from '../components/cards/BlogCard';
import CTASection from '../components/sections/CTASection';
import { usePage, usePosts, useSection } from '../hooks/useApi';

export default function Blog() {
  const [category, setCategory] = useState('All');
  const { page } = usePage('blog');
  const { data: blogs, loading, error, reload } = usePosts();
  const { items: categoryItems } = useSection('blog_categories');
  const blogCategories = ['All', ...categoryItems.map((c) => c.title)];

  const visible = useMemo(() => {
    const list =
      category === 'All' ? blogs : blogs.filter((b) => b.category === category);
    return [...list].sort((a, b) => new Date(b.publishedAt) - new Date(a.publishedAt));
  }, [category, blogs]);

  return (
    <>
      <SEO page={page} />

      <PageHero page={page} trail={[{ label: 'Blog' }]} />

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

          <AsyncState loading={loading} error={error} empty={blogs.length === 0} onRetry={reload} emptyText="No articles published yet.">
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
          </AsyncState>
        </div>
      </section>

      <CTASection
        cta={page?.cta}
        primary={{ label: 'Talk to an Expert', to: '/contact' }}
        secondary={{ label: 'View Services', to: '/services' }}
      />
    </>
  );
}
