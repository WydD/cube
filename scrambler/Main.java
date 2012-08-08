import com.yahoo.platform.yui.compressor.CssCompressor;
import com.yahoo.platform.yui.compressor.JavaScriptCompressor;
import org.mozilla.javascript.ErrorReporter;
import org.mozilla.javascript.EvaluatorException;

import java.io.*;
import java.nio.channels.FileChannel;

/**
 * @author: Loic Petit
 */
public class Main {
    static String prototype = "prototype-1.6.1_rc2";
    static String[] scriptaculous = {"builder", "effects", "dragdrop", "controls", "slider", "sound"};

    private static ErrorReporter errorReporter = new ErrorReporter() {
        public void warning(String message, String sourceName,
                            int line, String lineSource, int lineOffset) {

        }

        public void error(String message, String sourceName,
                          int line, String lineSource, int lineOffset) {
            if (line < 0) {
                System.err.println("\n[ERROR] " + message);
            } else {
                System.err.println("\n[ERROR] " + line + ':' + lineOffset + ':' + message);
            }
        }

        public EvaluatorException runtimeError(String message, String sourceName,
                                               int line, String lineSource, int lineOffset) {
            error(message, sourceName, line, lineSource, lineOffset);
            return new EvaluatorException(message);
        }
    };
    private static String assets;

    public static void scanSource(String path) throws IOException {
        scanSourceTree(new File(path), false, path);
    }

    private static String jsFile = "";
    private static String cssFileIE6 = "";
    private static String cssFileIE7 = "";
    private static String cssFile = "";

    public static void scanSourceTree(File source, boolean toString, String path) throws IOException {
        String res = "";
        if (source.isFile()) {
            FileReader fr = new FileReader(source);
            if (source.getName().endsWith(".js"))
                jsFile += getJS(toString, path, fr);
            else if (source.getName().endsWith("ie6.css"))
                cssFileIE6 += getCSS(path, fr);      
            else if (source.getName().endsWith("ie7.css"))
                cssFileIE7 += getCSS(path, fr);
            else if (source.getName().endsWith(".css"))
                cssFile += getCSS(path, fr);
            else
                copyFile(source, assets);

            fr.close();
        } else if (source.isDirectory() && !source.getName().endsWith(".svn")) {
            File[] childs = source.listFiles();
            if (childs == null) return;
            for (File f : childs)
                scanSourceTree(f, toString, path + "/" + f.getName());
        }
    }

    private static void copyFile(File source, String assets) throws IOException {
        File target = new File(assets + source.getName());
        FileChannel inChannel = new FileInputStream(source).getChannel();
        FileChannel outChannel = new FileOutputStream(target).getChannel();
        try {
            inChannel.transferTo(0, inChannel.size(),
                    outChannel);
        } finally {
            if (inChannel != null) inChannel.close();
            if (outChannel != null) outChannel.close();
        }

    }

    private static String getCSS(String path, FileReader fr) throws IOException {
        System.out.println("Parsing " + path);
        CssCompressor compressor = new CssCompressor(fr);
        StringWriter sw = new StringWriter();
        compressor.compress(sw, -1);
        return sw.toString();
    }

    private static String getJS(boolean toString, String path, FileReader fr) throws IOException {
        System.out.println("Parsing " + path);
        JavaScriptCompressor compressor = new JavaScriptCompressor(fr, errorReporter);
        StringWriter sw = new StringWriter();
        compressor.compress(sw, -1, true, true, true, true);
        String res = sw.toString();
        if (toString) {
            res = res.replace("\\", "\\\\");
            res = res.replace("\"", "\\\"");
            res = "jPlex.src['" + path + "']=\"" + res + "\";";
        }
        return res;
    }


    public static void main(String args[]) {
        try {
            String path = "";
            assets = path + "/assets/";
            File source = new File("src/scrambles");
            if (!source.exists()) {
                System.err.println("Wrong source dir");
                System.exit(2);
            }
                 /*           
            scanSource(args[0]+"libs/" + prototype + ".js");
            for (String s : scriptaculous)
                scanSource(args[0]+"libs/" + s + ".js");
                    */          
            scanSource("src/jplex.js");
            scanSource("src/raphael-min.js");
            scanSourceTree(source, true, "scrambles");
            writeFile(path + "test.js", jsFile);
            //writeFile(assets + "jplex-ie6.css", cssFileIE6);
            //writeFile(assets + "jplex-ie7.css", cssFileIE6);
            //writeFile(assets + "jplex.css", cssFile);
        } catch (IOException e) {
            e.printStackTrace();
        }
    }

    private static void writeFile(String filename, String content) throws IOException {
        FileWriter f = new FileWriter(filename);
        f.append(content);
        f.close();
    }
}
