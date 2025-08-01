import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:sam_app/core/routing/app_routes.dart';
import 'package:sam_app/core/themes/app_theme.dart';
import 'package:sam_app/data/repositories/publicacao/feed_repository.dart';
import 'package:sam_app/domain/viewmodels/publicacao/feed_curso_viewmodel.dart';
import 'package:sam_app/domain/viewmodels/publicacao/feed_geral_viewmodel.dart';
import 'package:sam_app/domain/viewmodels/splash_viewmodel.dart';
import 'package:sam_app/presentation/pages/artigos/artigos_page.dart';

import 'package:sam_app/presentation/pages/feed/feed_page.dart';
import 'package:sam_app/presentation/pages/forms/formulario_page.dart';
import 'package:sam_app/presentation/pages/grupos/grupos_estudo_page.dart';
import 'package:sam_app/presentation/pages/home_page.dart';
import 'package:sam_app/presentation/pages/instituicoes_page.dart';
import 'package:sam_app/presentation/pages/login_page.dart';

import 'data/storage/auth_storage_service.dart';
import 'domain/viewmodels/login_viewmodel.dart';
import 'presentation/pages/splash_page.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  final storageService = await AuthStorageService.init();
  final feedRepository = FeedRepository();

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
        AppRoutes.feed: (_) => const FeedPage(),

        AppRoutes.gruposEstudo: (_) => const GruposEstudoPage(),
        AppRoutes.formularios: (_) => const FormulariosPage(),
        AppRoutes.artigos: (_) => const ArtigosPage()
      },
    );
  }
}
