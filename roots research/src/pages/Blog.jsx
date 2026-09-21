import { Newspaper } from "lucide-react";
import PageHero from "../components/layout/PageHero";
import Section from "../components/ui/Section";
import Reveal from "../components/ui/Reveal";
import ArticleCard from "../components/ui/ArticleCard";
import CTABanner from "../components/sections/CTABanner";
import { articles } from "../data/blog";

export default function Blog() {
  return (
    <>
      <PageHero
        eyebrow="The Journal"
        eyebrowIcon={Newspaper}
        title="Research & Writing Insights"
        description="Practical, mentor-written articles on the craft of academic research, statistical analysis and thesis defence."
      />
      <Section>
        <div className="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
          {articles.map((article, i) => (
            <Reveal key={article.title} delay={Math.min(i * 0.08, 0.4)}>
              <ArticleCard article={article} />
            </Reveal>
          ))}
        </div>
      </Section>
      <CTABanner
        title="Want Insights Like This In Your Inbox?"
        description="Subscribe for a monthly digest of research-writing tips from our mentor network."
        ctaLabel="Subscribe via Contact Form"
      />
    </>
  );
}
