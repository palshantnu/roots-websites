import { Routes, Route, useLocation } from "react-router-dom";
import { AnimatePresence, motion } from "framer-motion";
import { ThemeProvider } from "./context/ThemeContext";
import { ToastProvider } from "./context/ToastContext";
import Navbar from "./components/layout/Navbar";
import Footer from "./components/layout/Footer";
import ScrollToTop from "./components/layout/ScrollToTop";

import Home from "./pages/Home";
import Services from "./pages/Services";
import ResearchSupport from "./pages/ResearchSupport";
import PublicationSupport from "./pages/PublicationSupport";
import Mentors from "./pages/Mentors";
import Blog from "./pages/Blog";
import MockVivaPage from "./pages/MockVivaPage";
import WorkshopsPage from "./pages/WorkshopsPage";
import About from "./pages/About";
import Careers from "./pages/Careers";
import Privacy from "./pages/Privacy";
import Terms from "./pages/Terms";
import NotFound from "./pages/NotFound";

function PageTransition({ children }) {
  return (
    <motion.div
      initial={{ opacity: 0, y: 12 }}
      animate={{ opacity: 1, y: 0 }}
      exit={{ opacity: 0, y: -12 }}
      transition={{ duration: 0.35, ease: [0.22, 1, 0.36, 1] }}
    >
      {children}
    </motion.div>
  );
}

function AnimatedRoutes() {
  const location = useLocation();
  return (
    <AnimatePresence mode="wait" initial={false}>
      <Routes location={location} key={location.pathname}>
        <Route path="/" element={<PageTransition><Home /></PageTransition>} />
        <Route path="/services" element={<PageTransition><Services /></PageTransition>} />
        <Route path="/research-support" element={<PageTransition><ResearchSupport /></PageTransition>} />
        <Route path="/publication-support" element={<PageTransition><PublicationSupport /></PageTransition>} />
        <Route path="/mentors" element={<PageTransition><Mentors /></PageTransition>} />
        <Route path="/blog" element={<PageTransition><Blog /></PageTransition>} />
        <Route path="/mock-viva" element={<PageTransition><MockVivaPage /></PageTransition>} />
        <Route path="/workshops" element={<PageTransition><WorkshopsPage /></PageTransition>} />
        <Route path="/about" element={<PageTransition><About /></PageTransition>} />
        <Route path="/careers" element={<PageTransition><Careers /></PageTransition>} />
        <Route path="/privacy" element={<PageTransition><Privacy /></PageTransition>} />
        <Route path="/terms" element={<PageTransition><Terms /></PageTransition>} />
        <Route path="*" element={<PageTransition><NotFound /></PageTransition>} />
      </Routes>
    </AnimatePresence>
  );
}

export default function App() {
  return (
    <ThemeProvider>
      <ToastProvider>
        <ScrollToTop />
        <div className="noise-overlay" aria-hidden="true" />
        <div className="flex min-h-screen flex-col bg-white dark:bg-ink-950">
          <Navbar />
          <main className="flex-1">
            <AnimatedRoutes />
          </main>
          <Footer />
        </div>
      </ToastProvider>
    </ThemeProvider>
  );
}
