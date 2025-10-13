import 'package:flutter/material.dart';
import 'package:flutter_quill/flutter_quill.dart';
import 'package:provider/provider.dart';
import 'package:sam_app/core/routing/app_routes.dart';
import 'package:sam_app/core/themes/app_theme.dart';
import 'package:sam_app/data/repositories/artigo/artigo_repository.dart';
import 'package:sam_app/data/repositories/formulario/formulario_repository.dart';
import 'package:sam_app/data/repositories/grupo_estudo/grupos_estudo_repository.dart';
import 'package:sam_app/data/repositories/publicacao/feed_repository.dart';
import 'package:sam_app/domain/viewmodels/artigo/artigos_criados_viewmodel.dart';
import 'package:sam_app/domain/viewmodels/artigo/artigos_explorar_viewmodel.dart';
import 'package:sam_app/domain/viewmodels/formulario/formularios_criados_viewmodel.dart';
import 'package:sam_app/domain/viewmodels/formulario/formularios_explorar_viewmodel.dart';
import 'package:sam_app/domain/viewmodels/grupo_estudo/grupos_criados_viewmodel.dart';
import 'package:sam_app/domain/viewmodels/grupo_estudo/grupos_ingressados_viewmodel.dart';
import 'package:sam_app/domain/viewmodels/grupo_estudo/grupos_populares_viewmodel.dart';
import 'package:sam_app/domain/viewmodels/publicacao/feed_curso_viewmodel.dart';
import 'package:sam_app/domain/viewmodels/publicacao/feed_geral_viewmodel.dart';
import 'package:sam_app/domain/viewmodels/splash_viewmodel.dart';
import 'package:sam_app/presentation/pages/artigos/artigos_page.dart';

import 'package:sam_app/presentation/pages/formularios/formularios_page.dart';
import 'package:sam_app/presentation/pages/grupos/grupos_estudo_page.dart';
import 'package:sam_app/presentation/pages/home_page.dart';
import 'package:sam_app/presentation/pages/instituicoes_page.dart';
import 'package:sam_app/presentation/pages/login_page.dart';

import 'data/storage/auth_storage_service.dart';
import 'domain/viewmodels/login_viewmodel.dart';
import 'presentation/pages/splash_page.dart';

import 'package:flutter_localizations/flutter_localizations.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  final storageService = await AuthStorageService.init();
  final feedRepository = FeedRepository();
  final grupoRepository = GruposEstudoRepository();
  final formRepository = FormularioRepository();

  final artigoRepository = ArtigoRepository();

  runApp(
    MultiProvider(
      providers: [
        /// Storage
        Provider<AuthStorageService>.value(value: storageService),
        Provider<FeedRepository>.value(value: feedRepository),

        /// Login
        ChangeNotifierProvider(
          create: (context) => LoginViewModel(storageService),
        ),

        /// Splash
        Provider(create: (context) => SplashViewModel(storageService)),

        /// Feed geral
        ChangeNotifierProvider<FeedGeralViewmodel>(
          create: (_) => FeedGeralViewmodel(feedRepository),
        ),

        /// Feed curso
        ChangeNotifierProvider<FeedCursoViewmodel>(
          create: (_) => FeedCursoViewmodel(feedRepository),
        ),

        /// Grupos ingressados
        ChangeNotifierProvider<GruposIngressadosViewmodel>(
          create: (_) => GruposIngressadosViewmodel(grupoRepository),
        ),

        /// Grupos criados
        ChangeNotifierProvider<GruposCriadosViewmodel>(
          create: (_) => GruposCriadosViewmodel(grupoRepository),
        ),

        /// Grupos populares
        ChangeNotifierProvider<GruposPopularesViewmodel>(
          create: (_) => GruposPopularesViewmodel(grupoRepository),
        ),

        /// Formulários explorar
        ChangeNotifierProvider<FormulariosExplorarViewmodel>(
          create: (_) => FormulariosExplorarViewmodel(formRepository),
        ),

        /// Formulários criados
        ChangeNotifierProvider<FormulariosCriadosViewmodel>(
          create: (_) => FormulariosCriadosViewmodel(formRepository),
        ),

        /// Artigos explorar
        ChangeNotifierProvider<ArtigosExplorarViewmodel>(
          create: (_) => ArtigosExplorarViewmodel(artigoRepository),
        ),

        /// Artigos criados
        ChangeNotifierProvider<ArtigosCriadosViewmodel>(
          create: (_) => ArtigosCriadosViewmodel(artigoRepository),
        ),
      ],
      child: MyApp(),
    ),
  );
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      debugShowCheckedModeBanner: false,
      theme: AppTheme.darkTheme,
      initialRoute: AppRoutes.splash,
      routes: {
        AppRoutes.splash: (_) => const SplashPage(),
        AppRoutes.login: (_) => const LoginPage(),
        AppRoutes.instituicoes: (_) => const InstituicoesPage(),

        AppRoutes.home: (_) => const HomePage(),

        AppRoutes.gruposEstudo: (_) => const GruposEstudoPage(),
        AppRoutes.formularios: (_) => const FormulariosPage(),
        AppRoutes.artigos: (_) => const ArtigosPage(),
      },
      localizationsDelegates: const [
        FlutterQuillLocalizations.delegate,
        GlobalMaterialLocalizations.delegate,
        GlobalWidgetsLocalizations.delegate,
        GlobalCupertinoLocalizations.delegate,
        FlutterQuillLocalizations.delegate,
      ],
      supportedLocales: const [Locale('en'), Locale('pt')],
    );
  }
}
