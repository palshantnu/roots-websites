import { ArrowUpRight, Newspaper } from "lucide-react";
import Section from "../ui/Section";
import SectionHeading from "../ui/SectionHeading";
import Reveal from "../ui/Reveal";
import Button from "../ui/Button";
import ArticleCard from "../ui/ArticleCard";
import { usePosts } from "../../hooks/useApi";

export default function BlogPreview() {
  const { data: articles } = usePosts();

  return (
    <Section>
      <div className="flex flex-col items-center justify-between gap-8 sm:flex-row sm:items-end">
        <SectionHeading
          align="left"
          eyebrow="From the Journal"
          eyebrowIcon={Newspaper}
          title="Research & Writing Insights"
          description="Practical, mentor-written articles on the craft of academic research — no fluff, just what actually moves your thesis forward."
          className="sm:max-w-xl"
        />
        <Reveal delay={0.15} className="shrink-0">
          <Button to="/blog" variant="outline" icon={ArrowUpRight}>
            View All Articles
          </Button>
        </Reveal>
      </div>

      <div className="mt-14 grid grid-cols-1 gap-6 md:grid-cols-3">
        {articles.slice(0, 3).map((article, i) => (
          <Reveal key={article.id} delay={i * 0.1}>
            <ArticleCard article={article} tone={i % 2 ? "blue" : "ink"} />
          </Reveal>
        ))}
      </div>
    </Section>
  );
}
