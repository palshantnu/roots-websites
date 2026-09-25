import {
  Award, BadgeCheck, BarChart3, BookMarked, BookOpen, BookText, Briefcase,
  CalendarClock, CheckCircle2, ClipboardCheck, Clock3, Compass, Database,
  FileCheck2, FileSearch, FileStack, FileText, FlaskConical, GitBranch, Globe,
  GraduationCap, HeadphonesIcon, Heart, Layers, LineChart, ListChecks, Lock,
  MessagesSquare, Mic, Network, Newspaper, PenTool, Quote, Search, SearchCheck,
  ShieldAlert, ShieldCheck, Sparkles, Target, Timer, Users2, Zap,
} from "lucide-react";

// Icons the admin panel can pick for services and section items. Keep this
// list in sync with `research.icons` in backend/config/sites.php.
const icons = {
  Award, BadgeCheck, BarChart3, BookMarked, BookOpen, BookText, Briefcase,
  CalendarClock, CheckCircle2, ClipboardCheck, Clock3, Compass, Database,
  FileCheck2, FileSearch, FileStack, FileText, FlaskConical, GitBranch, Globe,
  GraduationCap, HeadphonesIcon, Heart, Layers, LineChart, ListChecks, Lock,
  MessagesSquare, Mic, Network, Newspaper, PenTool, Quote, Search, SearchCheck,
  ShieldAlert, ShieldCheck, Sparkles, Target, Timer, Users2, Zap,
};

export function getIcon(name, fallback = Sparkles) {
  return icons[name] ?? fallback;
}
