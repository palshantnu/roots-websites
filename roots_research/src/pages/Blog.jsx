import { Newspaper } from "lucide-react";
import PageHero from "../components/layout/PageHero";
import Section from "../components/ui/Section";
import Reveal from "../components/ui/Reveal";
import ArticleCard from "../components/ui/ArticleCard";
import CTABanner from "../components/sections/CTABanner";
import { usePage, usePosts } from "../hooks/useApi";

export default function Blog() {
  const { page } = usePage("blog");
  const { data: articles } = usePosts();

  return (
    <>
      <PageHero page={page} eyebrowIcon={Newspaper} />
      <Section>
        <div className="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
          {articles.map((article, i) => (
            <Reveal key={article.id} delay={Math.min(i * 0.08, 0.4)}>
              <ArticleCard article={article} tone={i % 2 ? "blue" : "ink"} />
            </Reveal>
          ))}
        </div>
      </Section>
      <CTABanner cta={page?.cta} />
    </>
  );
}
